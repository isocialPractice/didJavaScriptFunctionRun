// didJavaScriptFunctionRun
// Check if a JavaScript function was run in the browser and return 1 (yes) or 0 (no).

 // Global variables with unlikely names.
 var didJavaScriptFunctionRunCheck, didJavaScriptFunctionRunCheck_randomCharacters, hideCheck, didJavaScriptFunctionRun_ID;

function didJavaScriptFunctionRun() {
 // Global variables with unlikely names.
 didJavaScriptFunctionRunCheck = 0;
 didJavaScriptFunctionRunCheck_randomCharacters = "";
 hideCheck = document.getElementById("hideCheck");
 didJavaScriptFunctionRun_ID = document.getElementById("didJavaScriptFunctionRunID");
 // make a random sequence using default parameters
 var makeRandomSequence = function(
  randomLength = 8, 
  randomData = [ "digits", "lowerCaseCharacters", "upperCaseCharacters" ]
 ) {
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
 var runCheckJSXMLHttp = function(curFile) {
  // redefine with each call
  didJavaScriptFunctionRunCheck_randomCharacters = ""; 
  // call to make random sequence
  makeRandomSequence(8, ["lowerCaseCharacters", "upperCaseCharacters"]); 
  // store output in variable  
  let outRandomSequence = "";
  let checkJSxmlhttp = new XMLHttpRequest();
  checkJSxmlhttp.onreadystatechange = function() {   
   if (this.readyState == 4 && this.status == 200) {
    if (runCount == 0) {     
     if (this.responseText.indexOf("yes") > -1) {
      didJavaScriptFunctionRunCheck = 1;     
     } else {
      didJavaScriptFunctionRunCheck = 0;
     }    
    } else if (runCount == 1) {
     outRandomSequence = "";
     if (this.responseText) {
      outRandomSequence = "";
      didJavaScriptFunctionRun_ID.value = this.responseText;
      hideCheck.style.display = "";
      // also log to the console - random characters and yes response
      console.log(this.responseText);
     }
    }
   }
  };
  if (runCount == 0) {
   outRandomSequence = "?" + didJavaScriptFunctionRunCheck_randomCharacters;    
  } else {
   outRandomSequence = "";
  }  
  /******************************************************************************
  // NOTE - the working use calls HTTP using "GET" method.
  //        This file is for example purposes.
  // checkJSxmlhttp.open("GET", curFile, true);  
  ******************************************************************************/  
  checkJSxmlhttp.open("POST", curFile + outRandomSequence, true);  
  checkJSxmlhttp.setRequestHeader(
   "Content-type", 
   "text/html"
  );
  checkJSxmlhttp.send();
  };
 runCheckJSXMLHttp("https://practicecode.xyz/didJavaScriptFunctionRun/scripts/make_random_file.php");
 
 setTimeout(function() {
  runCount = 1;
  runCheckJSXMLHttp(
   "https://practicecode.xyz/didJavaScriptFunctionRun/tmp/" + 
   didJavaScriptFunctionRunCheck_randomCharacters + 
   "/file.txt");
 }, 1000);
}
