// deleteTmpDidJavaScriptFunctionRun
// Deletes the tmp elements if no input or button elements were clicked.

 // Add disabled attribute to the form submit button.
 var formSubmittedDidJavaScriptFunctionRun = sessionStorage.getItem("formSubmittedDidJavaScriptFunctionRun");
 function checkFormSubmittedDidJavaScriptFunctionRun () {
  formSubmittedDidJavaScriptFunctionRun = sessionStorage.getItem("formSubmittedDidJavaScriptFunctionRun");
  if (formSubmittedDidJavaScriptFunctionRun != null) {
   let submitButtonDidJavaScriptRun = document.getElementById("submitButtonDidJavaScriptRun");
   if (submitButtonDidJavaScriptRun) {
    submitButtonDidJavaScriptRun.setAttribute("disabled", true); 
   } 
  }
 }
 
 // Set session storage item to 0 when page is first loaded, and added as event listenr to the
 // HTML input and button elements to check if clicked and if not removes tmp items when page closed.
 var pageLoadsDidJavaScriptFunctionRun = sessionStorage.getItem("pageLoadsDidJavaScriptFunctionRun");
 function storagePageLoadsDidJavaScriptFunctionRun() {
  pageLoadsDidJavaScriptFunctionRun= sessionStorage.getItem("pageLoadsDidJavaScriptFunctionRun");
  if (pageLoadsDidJavaScriptFunctionRun == null) {
   sessionStorage.setItem("pageLoadsDidJavaScriptFunctionRun", 0);
   pageLoadsDidJavaScriptFunctionRun = sessionStorage.getItem("pageLoadsDidJavaScriptFunctionRun");
  } else {
   sessionStorage.setItem("pageLoadsDidJavaScriptFunctionRun", Number(pageLoadsDidJavaScriptFunctionRun) + 1);
   pageLoadsDidJavaScriptFunctionRun = sessionStorage.getItem("pageLoadsDidJavaScriptFunctionRun");
  }
 }
 
 // Make AJAX call to delete tmp elements after form submitted or page closed with no click events 
 // on button or input elements.
 function cleanTmpDidJavaScriptFunctionRun(forceOut) {
  if (forceOut == undefined) { forceOut = 0; }
  storagePageLoadsDidJavaScriptFunctionRun();
  pageLoadsDidJavaScriptFunctionRun = sessionStorage.getItem("pageLoadsDidJavaScriptFunctionRun");
  let outxmlhttp = new XMLHttpRequest();
  if (pageLoadsDidJavaScriptFunctionRun == 0 || forceOut == 1) {
   let checkRandomVar;
   let theDeleteQuery;
   
   
   if (!didJavaScriptFunctionRunCheck_randomCharacters) {
    if (localStorage.getItem("didJavaScriptFunctionRun_localStorage") == null) {
     theDeleteQuery = "--_out_--";
    } else {
     theDeleteQuery = localStorage.getItem("didJavaScriptFunctionRun_localStorage");
    }    
   } else {
    theDeleteQuery = didJavaScriptFunctionRunCheck_randomCharacters;    
   }
   console.log(theDeleteQuery);
   
   if (theDeleteQuery == "--_out_--") {
    let skip;
   } else {    
    outxmlhttp.onreadystatechange = function() {
     if (this.readyState == 4 && this.status == 200) {
      console.log("removing tmp files - " + theDeleteQuery);
     }     
    };      
    outxmlhttp.open("GET", "/didJavaScriptFunctionRun/scripts/delete_random_file.php?" + theDeleteQuery, true);
    outxmlhttp.send();   
   }
  } 
 }
 
 // Add listener to remove tmp items if no input and button clicked and page closed.
 if (pageLoadsDidJavaScriptFunctionRun == null) {    
  window.addEventListener("beforeunload", cleanTmpDidJavaScriptFunctionRun);  
 } else {
  window.removeEventListener("beforeunload", cleanTmpDidJavaScriptFunctionRun);
 }
 
 // Add click check to button and input elements.
 var allButtonsDidJavaScriptFunctionRun, allInputsDidJavaScriptFunctionRun;
 function turnOffCleanTmpDidJavaScriptFunctionRun(curEl) {
  let len = curEl.length;
  if (len >= 1) {
   for (i = 0; i < len; i++) {
    curEl[i].addEventListener("click", storagePageLoadsDidJavaScriptFunctionRun);
   }
  }
 } 
 allInputsDidJavaScriptFunctionRun = document.getElementsByTagName("input");
 turnOffCleanTmpDidJavaScriptFunctionRun(allInputsDidJavaScriptFunctionRun);
 allButtonsDidJavaScriptFunctionRun = document.getElementsByTagName("button");
 turnOffCleanTmpDidJavaScriptFunctionRun(allButtonsDidJavaScriptFunctionRun);