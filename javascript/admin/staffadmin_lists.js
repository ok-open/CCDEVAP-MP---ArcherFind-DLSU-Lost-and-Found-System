// 1. JAVASCRIPT FOR TOGGLING THE SIDE PANEL WHEN ICON_SEE IS CLICKED
if (document.getElementById('SidePanel_Iconsee')) { 
    //This is needed for surrenderList.html toast message to work
    //It doesn't have a side panel, so we check first if there is a side panel to avoid null object
    const sidePanel = document.getElementById('SidePanel_Iconsee');
    const openButtons = document.querySelectorAll('.openPanelBtn'); //we use querySelectorAll because I used 'class', not 'id'
    const closePanelBtn = document.getElementById('closePanelBtn');

    openButtons.forEach((btn) => {
        btn.addEventListener('click', () => {sidePanel.classList.add('open')});
    });
    closePanelBtn.addEventListener('click', () => {sidePanel.classList.remove('open')});
}

// 2. TOGGLE THE POSSIBLE MATCH ITEM'S DETAIL IN REPORT-LIST
function toggleDetails(cardElement) {
    const details = cardElement.querySelector(".LostDetailsImgMatch");
    if (details) {
        details.classList.toggle("hidden");
    }
}

// 3. CONFIRMATION MESSAGE BEFORE RESOLVE OR CLOSE REPORT
function submitStatusAction(reportId, action) {
    const actionText = action === 'resolve' ? 'Resolve' : 'Close';
    const confirmMessage = `Do you wish to mark this report as '${actionText}?'`;
    
    if (confirm(confirmMessage)) {
        document.getElementById('formReportId').value = reportId;
        document.getElementById('formAction').value = action;
        document.getElementById('statusActionForm').submit();
    }
}

// 4. JAVASCRIPT FOR EXPANDING THE IMAGE
const modal = document.getElementById("ExpandPanel_ImgItem");
const largeImg = document.getElementById("imgExpand");
const allImages = document.querySelectorAll(".ImgItem");

allImages.forEach(function(img){
    img.onclick = function() {
        modal.style.display = "flex";
        largeImg.src = this.src;
    }
})


modal.onclick = function(event) {
    if (event.target === modal){
        modal.style.display = "none";
    }
}

// 4. JAVASCRIPT FOR THE IMAGE CAROUSELL
// Keeps track of the active slide index for each individual record (e.g., { "0": 1, "1": 3 })
let slideIndices = {};

// Next/previous controls (We pass the record index 'group' and the step 'n')
function plusSlides(n, group) {
  if (slideIndices[group] === undefined) {
    slideIndices[group] = 1;
  }
  showSlides(slideIndices[group] += n, group);
}

// Thumbnail/dot controls
function currentSlide(n, group) {
  showSlides(slideIndices[group] = n, group);
}

function showSlides(n, group) {
  let i;
  // Target only the slides and dots belonging to this specific record index group
  let slides = document.getElementsByClassName("slide-group-" + group);
  let dots = document.getElementsByClassName("dot-group-" + group);
  
  // If there are no images for this group, exit early
  if (slides.length === 0) return;

  if (slideIndices[group] === undefined) {
    slideIndices[group] = 1;
  }

  // Wrap around logic
  if (n > slides.length) { slideIndices[group] = 1; }
  if (n < 1) { slideIndices[group] = slides.length; }

  // Hide all slides in this group
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  // Deactivate all dots in this group
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active-img", "");
  }

  // Show the active slide and dot for this group
  slides[slideIndices[group] - 1].style.display = "block";
  if (dots[slideIndices[group] - 1]) {
    dots[slideIndices[group] - 1].className += " active-img";
  }
}

//JAVASCRIPT FOR TOAST MESSAGES
/*
const toastBox = document.getElementById('toastBox');

function showToast(message, status) {
    const toast = document.createElement('div');
    toast.classList.add('toast');
    
    toast.classList.add(status);
    
    let icon = 'ℹ️';
    if (status === 'success') icon = '✅';
    if (status === 'error') icon = '❌';
    
    toast.innerHTML = `<span>${icon}</span> <span>${message}</span>`;
    
    toastBox.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3500);
}

//TO ACCEPT CLAIM REQUEST
function onAcceptRequest() {
    showToast('Request accepted successfully!', 'success');
}

//TO REJECT CLAIM REQUEST
function onRejectRequest() {
    showToast('Request has been rejected.', 'error');
}

//TO ACCEPT SURRENDER FORM
function onAcceptSurrender() {
    showToast('Item has been added to database', 'success');
}

//TO REJECT SURRENDER FORM
function onRejectSurrender() {
    showToast('Form rejected, no item added to database', 'error');
}

//TO RESOLVE LOST ITEM REPORT
function onResolveLostReport() {
    showToast('Report is marked resolved', 'success');
}

//TO CLOSE LOST ITEM REPORT
function onCloseLostReport() {
    showToast('Report closed', 'error');
}

//For Error checking
function onSaveFailure() {
    showToast('Connection failed. Please try again.', 'error');
// } */

//JAVASCRIPT FOR TOAST MESSAGES (REVISED NOW REFLECTS TOAST.JS)
//TO ACCEPT CLAIM REQUEST
function onAcceptRequest() {
    showToast("✓ Request accepted successfully", "var(--color-successMsg)");
}

//TO REJECT CLAIM REQUEST
function onRejectRequest() {
    showToast("✗ Request has been rejected", "var(--color-errorMsg)");
}

//TO ACCEPT SURRENDER FORM
function onAcceptSurrender() {
    showToast("✓ Item has been added to database", "var(--color-successMsg)");
}

//TO REJECT SURRENDER FORM
function onRejectSurrender() {
    showToast("✗ Form rejected, no item added to database", "var(--color-errorMsg)");
}

//TO RESOLVE LOST ITEM REPORT
function onResolveLostReport() {
    showToast("✓ Report is marked resolved", "var(--color-successMsg)");
}

//TO CLOSE LOST ITEM REPORT
function onCloseLostReport() {
    showToast("✗ Report closed", "var(--color-errorMsg)");
}

//For Error checking
function onSaveFailure() {
    showToast("✗ Connection failed. Please try again.", "var(--color-errorMsg)");
}