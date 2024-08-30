// didJavaScriptFunctionRun
// Check if a JavaScript function was run in the browser and return 1 (yes) or 0 (no).

// Global variables with unlikely names.
var didJavaScriptFunctionRunCheck, didJavaScriptFunctionRunCheck_randomCharacters, didJavaScriptFunctionRunCheck_ID;
var didJavaScriptFunctionRun_localStorage;
var didJavaScriptFunctionRun_DEBUG = 0;
function didJavaScriptFunctionRun() {
 // define the global variables 
 didJavaScriptFunctionRunCheck_randomCharacters = ""; 
 didJavaScriptFunctionRunCheck_ID = document.getElementById("didJavaScriptFunctionRunID"); 
 didJavaScriptFunctionRunCheck_ID.removeAttribute("disabled");
 // make a random sequence using default parameters
 var makeRandomSequence = function(
  randomLength = 8, 
  randomData = [ "digits", "lowerCaseCharacters", "upperCaseCharacters" ]
 ) {
  didJavaScriptFunctionRunCheck_randomCharacters = ""; 
  let randomObject = {
   "digits": "0123456789",
   "lowerCaseCharacters": "abcdefghijklmnopqrstuvwxyz",
   "upperCaseCharacters": "ABCDEFGHIJKLMNOPQRSTUVWXYZ"  
  };  
  for (i = 0; i < randomLength; i++) {
   let randomDataType = // 0 to 2, or 0 to lenth of parameter randomData
    Math.floor(Math.random() * randomData.length);
    
   let randomDigit    = // random integer from length of randomObject property
    Math.floor(Math.random() * randomObject[randomData[randomDataType]].length);
                            // [ object ]  [ array    [ index ]   in   ^ ------ ]
    
   didJavaScriptFunctionRunCheck_randomCharacters  += // use above elements to append each random character
    randomObject[randomData[randomDataType]][randomDigit];
  }
 }; 
 
 // send to php to check
 let runCount = 0;
 if (localStorage.getItem("didJavaScriptFunctionRun_localStorage") == null) {
  makeRandomSequence(8, ["lowerCaseCharacters", "upperCaseCharacters"]);
  localStorage.setItem("didJavaScriptFunctionRun_localStorage", didJavaScriptFunctionRunCheck_randomCharacters);
  sessionStorage.setItem("didJavaScriptFunctionRunCheck_randomCharacters", didJavaScriptFunctionRunCheck_randomCharacters);
 } else {
  didJavaScriptFunctionRun_localStorage = localStorage.getItem("didJavaScriptFunctionRun_localStorage");
  didJavaScriptFunctionRunCheck_randomCharacters = didJavaScriptFunctionRun_localStorage;
 }
 var runCheckJSXMLHttp = function(curFile) {
  // toggle variable for random characters for query or empty value
  let outRandomSequence = "";
  let checkJSxmlhttp = new XMLHttpRequest();
  checkJSxmlhttp.onreadystatechange = function() {
   if (this.readyState == 4 && this.status == 200) {
    if (runCount === 0) {
     if (this.responseText.indexOf("yes") > -1) {
      didJavaScriptFunctionRunCheck = 1;
     } else {
      didJavaScriptFunctionRunCheck = 0;
     }    
    } else if (runCount == 1) {
     outRandomSequence = "";
     if (this.responseText) {
      outRandomSequence = "";
      didJavaScriptFunctionRunCheck = 1;
      didJavaScriptFunctionRunCheck_ID.value = this.responseText;
      didJavaScriptFunctionRunCheck_ID.style.display = "none";
      // log run result to console - FOR DEBUG
      if (didJavaScriptFunctionRun_DEBUG == 1) { console.log(this.responseText); }
     }
    }
   }
  };
  
  if (runCount === 0) {
   outRandomSequence = "?" + didJavaScriptFunctionRunCheck_randomCharacters;    
  } else {
   outRandomSequence = "";   
  }
  checkJSxmlhttp.open("GET", curFile + outRandomSequence, true);
  checkJSxmlhttp.send();
  };
 
 runCheckJSXMLHttp("/didJavaScriptFunctionRun/scripts/make_random_file.php");
 
 setTimeout(function() {
  runCount = 1;
  runCheckJSXMLHttp(
   "/didJavaScriptFunctionRun/tmp/" + 
   didJavaScriptFunctionRunCheck_randomCharacters + 
   "/file.txt");
 }, 500); 
}
