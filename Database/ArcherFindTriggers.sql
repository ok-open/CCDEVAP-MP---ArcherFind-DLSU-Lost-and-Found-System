-- TRIGGER 1
-- Tracks changes to the Status of a Report
-- Example: When staff clicks "Mark as Resolved" or "Close Report" on the Claim Requests, Lost Reports, or Surrender Forms Page
-- BACKEND CODE: Just need to do UPDATE reports SET status = 'Resolved' WHERE report_id = 12;
DELIMITER $$
CREATE TRIGGER log_ReportStatusChange
AFTER UPDATE ON reports
FOR EACH ROW
BEGIN
    -- 1. Log the report status change
    IF NOT (OLD.status <=> NEW.status) THEN
        INSERT INTO reports_update_log (report_id, staff_id, old_status, new_status)
        VALUES (OLD.report_id, NEW.reviewed_by, OLD.status, NEW.status);
    END IF;

    -- 2. CLAIM REQUEST CASE: Update the item status if a Claim Request is resolved
    IF OLD.status != NEW.status AND NEW.type = 'Claim request' AND NEW.status = 'Resolved' AND NEW.item_id IS NOT NULL THEN
        
        -- This statement will trigger TRIGGER 2
        UPDATE items 
        SET status = 'Claimed', 
			claimed_by = NEW.student_id
        WHERE item_id = NEW.item_id;
        
    END IF;
END $$
DELIMITER ;


-- TRIGGER 2
-- Tracks changes to the Status of an Item. NOTE! The status change of an item are based on the status of its report. Hence, Trigger 1
-- EXAMPLE: Claim Request for ITEM_ID = 1 gets Resolved --> Item Record with ITEM_ID 1 status = 'Claimed'
DELIMITER $$
CREATE TRIGGER log_ItemStatusChange
AFTER UPDATE ON items
FOR EACH ROW
BEGIN

    IF NOT (OLD.status <=> NEW.status) THEN
        INSERT INTO items_update_log (item_id, old_status, new_status)
        VALUES (OLD.item_id, OLD.status, NEW.status);
    END IF;
END $$
DELIMITER ;

-- TRIGGER 3
-- Inserts a new ITEM record when a Surrender Form gets "Marked as Resolved". 
-- Gets the information from the Report and inserts into the new ITEM record
-- BACKEND CODE: Just need to do UPDATE reports SET status = 'Resolved' WHERE report_id = 12; When this SQL is run, the INSERT statement happens automatically
DELIMITER $$
CREATE TRIGGER manage_SurrenderFormApproval
AFTER UPDATE ON reports
FOR EACH ROW
BEGIN
    -- Check if a Surrender Form was just marked as 'Resolved' / 'Accepted'
    IF OLD.status != NEW.status AND NEW.type = 'Surrender Form' AND NEW.status = 'Resolved' THEN
        
        -- Insert the newly discovered item into the items table
        INSERT INTO items (name, description, category_id, brand_id, surrendered_by, room_id, area_id, when_found, status)
        VALUES (NEW.item_name, NEW.item_description, NEW.category_id, NEW.brand_id, OLD.student_id, NEW.room_id, NEW.area_id, NEW.when_found, 'In Storage');
        -- For the moving of the images, and the new record in the items_images table. It will be handled in the backend because we need to change the file path pa
    END IF;
END $$
DELIMITER ;
