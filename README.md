# Did JavaScript Function Run ![repo icon](favicon.png)

<details>
<summary>Click to Show Navigation</summary>

  - [DISCLAIMER AND USE AGREEMENT](#legal-disclaimer-and-use-agreement)
  - [Template Overview](#template-overview)
  - [Template Files and Scripts](#template-files-and-scripts)
  - [Instructions](#instructions)
  - [Source Files](#source-files)

</details>

<br>

`Ctrl + click` to see example of [didJavaScriptFunctionRun.js](https://jhauga.github.io/htmlpreview.github.com/?https://github.com/isocialPractice/didJavaScriptFunctionRun/blob/main/index.html)

<!--`Ctrl + click` to view instructional video on [![YouTube](support/video.jpg)](CHANGE)-->

First and most importantly - be aware that incorrectly implementing this template can cause unexpected results; and that editing any of the files located in the ` didJavaScriptFunctionRun/scripts ` folder can possibly delete all files and folders on you machine. Both local and remote. If you edit be sure you know what you're doing!

## LEGAL DISCLAIMER AND USE AGREEMENT

By using this template, you acknowledge and agree to the following terms:

1. **Implementation Responsibility**: You are solely responsible for the implementation of this template. Any deviation from the illustrated and/or provided order of operations in this repository is at your own risk.

2. **Modification Warning**: Altering any support files or scripts in the "didJavaScriptFunctionRun" folder **could potentially clear all files and folders on the server**. Linking, including, or requiring the support "scripts" in any way that does not match the main order of operations illustrated in the examples, instructions, and links provided in this repository could prevent the form from submitting and/or produce unexpected results.

3. **Liability Waiver**: You agree not to hold the creator of this template repository responsible for any file or data loss on the server, or any other results deemed malicious, if this template is implemented, altered, edited, or linked to and set up on the webpage differently than illustrated in the provided examples and instructions.

4. **Usage License**: This template repository is public domain and licensed under CC0, allowing use for any purpose, commercial or private. However, it cannot be redistributed under a different license. You can alter and re-license it, but not re-license it as is, how it is published in this repository, or from the initial download state of the repository (<em>at any time</em>).

By using this template, you accept and agree to these terms.


## Template Overview

This template requires linking supporting JavaScript and PHP files in the order specified in the instructions and/or example(s). Unless you have an intermediate to expert knowledge of JavaScript and PHP, it is recommended to follow the sequence of support scripts and files as illustrated in the examples and instructions provided in this repository.

The template is designed for simple forms. A good example use for this template is a feedback form with one to several severl form elements (<em>other than submit button</em>). An example use of this template is provided. On the example page, click "Show Example Use" to expand simple use case that includes snippets of how this template should be implemented. `Ctrl + click` to see an example of [didJavaScriptFunctionRun.js](https://jhauga.github.io/htmlpreview.github.com/?https://github.com/jhauga/didJavaScriptFunctionRun/blob/main/index.html).

The goal of this template is to check that the page was loaded in a browser, and then - on the server; handle the response accoringly. How the server hanldes the reponse is to this template what replacing images and copy is to a promotional banner template. 

How the template works - first it checks to see if JavaScript was run in the browser by making an AJAX call using a php file. It creates random characters, passing them as query data in the AJAX call to the php file. This is then used to alter the HTML of a hidden form input element. Also, the php file used in the AJAX call uses the random characters to create files on the server that are utilized by support php and javascript scripts to achieve the end goal of the template - a variable storing yes (<em>1</em>) \| no (<em>0</em>). 



## Template Files and Scripts:

<details>
<summary>The main function <strong>didJavaScriptFunctionRun.js</strong> in "didJavaScriptFunctionRun" folder will: </summary>

1. Call the main function `didJavaScriptFunctionRun()` which sets a switch, and genereates a random sequence of data
according to the switch status.
2. Uses AJAX to ouput to and store form data for php.
3. Makes a random php file from that ouput.
4. Uses the **make_random_file.php** file to update the form field (<em>see instructions below</em>) via AJAX response.
   - The form data is then used in the php file "didJavaScriptFunctionRun.php" to store the output in the php variable 
` $didJavaScriptFunctionRunID `; which is then utilized by supporting scripts.

</details>

<details>
<summary>The <strong>didJavaScriptFunctionRun.php</strong> file in the "didJavaScriptFunctionRun" folder will:</summary>

1. Get the value that is parsed into the hidden input element with ` name="didJavaScriptFunctionRunID" `, termed 
<strong>didJavaScriptFunctionRunID</strong> for documentation.
2. Set switch variables according to the value in **didJavaScriptFunctionRunID**.
3. Use the value of **didJavaScriptFunctionRunID** to check againgst files in the "didJavaScriptFunctionRun/tmp" folder.
4. Utilize switch variables and php file handling functions to set final switch variables that are then handled by supporting scripts.
</details>

<strong>IMPORTANT SECURITY</strong> - The below file should not be altered. Especially the lines utulizing "shell_exec()".

<strong>IMPORTANT SECURITY</strong> - If the statements passed to "shell_exec()" include a wildcard i.e. `.*` in 
lieu of `*` all files and folders will be deleted. If altered and ` -maxdepth 1 ` is removed - ALL SERVER FILES REMOVED!

<details>
<summary>The <strong>make_random_file.php</strong> file in the "didJavaScriptFunctionRun/scripts" folder will: </summary>

<strong>IMPORTANT</strong> - here be mosters - on step 1.

1. Delete any random folders and files in the "tmp" folder that are 1 day or older.
   - **IMPORTANT SECURITY** - if the executables for this step are altered the server 
   could be cleared- all files and folders deleted.
   - **IMPORTANT** - unless you have an expert understanding of php **DO NOT** edit the 
   satements passed to the ` shell_exec() ` calls.
   - **NOTE** - these are only deleted when the page is opened in a browser so you may 
     want to periodically check and see if all random folders are deleted.
   - Please report any issues or vulnerabilites with this.
2. Call the function ` check_header_did_javascript_function_run() ` in "check_header_did_javascript_function_run.php" 
to check the headers of the request, setting switch variables accordingly.
3. Use query string from the random characters generated in didJavaScriptFunctionRun() call
to create random files and folders.
   - **NOTE** - the query string is cleaned, but please report any vulnerablities found.
4. Use these files to verify JavaScript ran.
5. Delete the duplicate of **template_check.php**.
6. After 5 minutes delete random folder and file created in "tmp" folder.
   - **NOTE** - this will start a process on the server for 5 minutes.
   - **NOTE** - if no input or button HTML elements are clicked and the page is closed an AJAX call
   to "deleteTmpDidJavaScriptFunctionRun.js" is made, which will delete "tmp" files; ending the process.
   - Please report any issues or vulnerabilites with this.

</details>

<details>
<summary>The <strong>template_check.php</strong> file in the "didJavaScriptFunctionRun/scripts" folder will: </summary>

1. Uses the random characters from make_random_file.php 
to output contents of text file (*should be yes*) for initial JavaScript check
2. Will delete the text file afterwards.

</details>

<details>
<summary>The <strong>deleteTmpDidJavaScriptFunctionRun.js</strong> file in the "didJavaScriptFunctionRun/scripts" folder will: </summary>
1. Make a sessionStorage variable set to 0 on first page load.
2. Adds an event listenr to the HTML window object that checks for page close or reload.
3. Adds an event listener to HTML input and button elements that removes the window listener from "2".
4. If no button or input elements have been clicked then AJAX is used to call **delete_random_file.php**,
which will delete the random "tmp" files that were created for JavaScript check.
</details>

<details>
<summary>The <strong>delete_random_file.php</strong> file in the "didJavaScriptFunctionRun/scripts" folder will: </summary>

1. Be called when the window closes, page is refreshed, or after the form is submitted.
2. Delete the "tmp" files if they still exists on the server.
   - this will end any processes that have been prolonged in regards to removing the tmp files.
</details>

<details>
<summary>The <strong>clean_random_character_function.php</strong> file in the "didJavaScriptFunctionRun/scripts" folder will:</summary>

1. Clean the random characters or data passed to the **didJavaScriptFunctionRunID** hidden 
form/input element so the support scripts can handle the data correctly.
   - Mainly this ensures that the "tmp" folder is the target for creating, checking, and deleting the
   tmp files that are used to check if JavaScript ran on the server.
</details>

<details>
<summary>The <strong>check_header_did_javascript_function_run.php.php</strong> file in the "didJavaScriptFunctionRun/scripts" folder will: </summary>

1. Get the header information for the request.
2. Determine if the request was made using the command line tool curl, and turns on a switch 
variable if so; which is used in further conditions.
   - The switch variable is used in php files that handle form requests. When it is turned on 
   nothing will happen in the supporting php script.
</details>

<details>
<summary>The <strong>did_java_script_function_run.php</strong> file in "didJavaScriptFunctionRun/scripts" folder will:</summary>

1. Utilize the elements from prior scripts to store a value of 1 or 0 in the variable
"$didJavaScriptFunctionRun".
   - This is the main goal of the template.
2. Enables the server to handle actions according to whether or not the page was loaded 
in a browser or not.
</details>


## Instructions:

**NOTE** - implementing, linking, including, and requring support files and scripts in any way differently than what is instructed below may result in unexpected results i.e. form not submitting.

**SECURITY NOTE** - changing, editing, renaming, and/or moving support files and scripts could potentially lead to unexpected deletion of files and folders on the server. Modifying commands passed to scripts utilizing the php function  ` shell_exec() ` could potentially **delete all files and folders on the server**.

On a brighter note - these steps are mostly copy/paste. The end result of step 1 is required and can be copied and pasted if using a Linux like OS. From step 2 to 5 this tool is intended to be implemented as instructed and is intended to be used on simple php/html forms that uses the form attribute **action** = ` "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" `. 

On step 6 (<em>last step</em>) the template can be utiilized. Change the marked area ` /* REPLACE WITH YOUR PHP */ ` with the code you want the server to execute when JavaScript does and does not run on an html/php form submission. To use/get started with this template:

1. **Step 1** - Clone this repository, and then **copy** the folder "**didJavaScriptFunctionRun**" into the **root** of the website (<em>have to with Windows OS</em>). Or copy/paste below to only get the repositories' folder "didJavaScriptFunctionRun" into a directory on your machine or directly into the site root:
```markdown
mkdir didJavaScriptFunctionRun &&\
cd didJavaScriptFunctionRun &&\
curl https://codeload.github.com/jhauga/didJavaScriptFunctionRun/tar.gz/main |\
tar -xz --strip=2 didJavaScriptFunctionRun-main/didJavaScriptFunctionRun &&\
cd ..
```

2. **Step 2** - Once the folder is in the site root paste the script below into the **HTML head** of the pages you want the tool to run. <br>
```markdown
<script src="/didJavaScriptFunctionRun/didJavaScriptFunctionRun.js"></script>
```

3. **Step 3** - Next copy and paste the below php code **above** the HTML **form element**. <br>
```markdown

<?php

 /* Change to a name of a required form element. */
 $name_of_required_form_field = "CHANGE_TO_REQUIRED_FORM_FIELD";
 
 /* Include didJavaScriptFunctionRun.php using absolut path from the root to
    didJavaScriptFunctionRun folder. */
 $did_javascript_function_run_path  = $_SERVER['DOCUMENT_ROOT'];   
 $did_javascript_function_run_path .= "/didJavaScriptFunctionRun";
 $did_javascript_function_run_PHP_FILE = $did_javascript_function_run_path . "/didJavaScriptFunctionRun.php";
 include_once($did_javascript_function_run_PHP_FILE);
 require($did_javascript_function_run_path . "/scripts/clean_random_character_function.php");
 
?>
 
```

4. **Step 4** - At the **beginning** of the HTML **form element** (<em>right after form opening tag</em>) copy and paste the below HTML, JavaScript, and php elements. <br>
```markdown

 <input type="text" value="<?php echo $didJavaScriptFunctionRunID; ?>" disabled style="display: none; border:none" id="didJavaScriptFunctionRunID" name="didJavaScriptFunctionRunID"> 
 <script>{let didJavaScriptFunctionRunCheck_ID = document.getElementById("didJavaScriptFunctionRunID"); 
 didJavaScriptFunctionRunCheck_ID.removeAttribute("disabled");let checkSessionDidJavaScriptFunctionRun = sessionStorage.getItem("didJavaScriptRun");if (checkSessionDidJavaScriptFunctionRun == null) { sessionStorage.setItem("didJavaScriptRun", "1"); didJavaScriptFunctionRun();} else { sessionStorage.removeItem("didJavaScriptRun"); let sessionDidJavaScriptFunctionRunCheck_randomCharacters = sessionStorage.getItem("didJavaScriptFunctionRunCheck_randomCharacters"); document.getElementById("didJavaScriptFunctionRunID").setAttribute("value", sessionDidJavaScriptFunctionRunCheck_randomCharacters); }}</script>  
 
```

   - **Step 4b** <em>optional</em> - Give the submit button's id the value of "submitButtonDidJavaScriptRun":
```markdoen
 id="submitButtonDidJavaScriptRun"
```
   - Or add to existing id for submit button:
```markdown
 submitButtonDidJavaScriptRun
```

5. **Step 5** - Paste the below **script** somewhere **before** step 6, around the **bottom** of page after the HTML form element. This deletes all tmp elements if no input or button elements have been clicked so the process from "make_random_file.php" won't continue to run after the page as closed.<br>
```markdown

<script src="/didJavaScriptFunctionRun/scripts/deleteTmpDidJavaScriptFunctionRun.js"></script>  


```

6. **Step 6** - Paste the below php code somewhere **after** the HTML **form element** to verify that JavaScript did run in the browser. Edit the marked area with the code you want to run when and when JavaScript does not run on a form submission.<br>
```markdown

<?php

 /* Making sure to include the absolut path from site root to didJavaScriptFunctionRun folder.       */
 /* Set path.                                                                                        */
 $did_javascript_function_run_path  = $_SERVER['DOCUMENT_ROOT'];   
 $did_javascript_function_run_path .= "/didJavaScriptFunctionRun"; 
 require($did_javascript_function_run_path . "/scripts/did_java_script_function_run.php");
 
 /* Check results from php script required above and run php accordingly.                            */
 if ($didJavaScriptFunctionRun == 1) {
  /* ***********************************************************************************             */
  /* START - PHP AS NEEDED *************************************************************             */
  /* CHANGE  -  PHP when JavaScript runs in browser. */
  /* mail("CHANGE@example.us", "Web Comment", $comment, $email); */
  /* ******************************************************************                              */



  /* ------ ----- ----- ----- 1 => JS __DID__ RUN ---- REPLACE WITH YOUR PHP ----- ----- ----- ----- */   



  /* NOTE - if no submit button id then add one and change it here or a variation of this method.    */
  /*        if not disabled form will not submit on second press and run will created random folder, */
  /*        leaving them there for a day before deleting. */
 
  /* END - PHP AS NEEDED ***************************************************************             */
  /* ***********************************************************************************             */
  /* NOTE - best to keep below lines, ensuring that the submit button has same id value.             */
  echo '
   <script>
    sessionStorage.setItem("formSubmittedDidJavaScriptFunctionRun", "1");
                                                               /* CHANGE TO SUBMIT BUTTON ID         */
    var submitButtonDidJavaScriptRun = document.getElementById("submitButtonDidJavaScriptRun");
    if (submitButtonDidJavaScriptRun) {
     setTimeout(function() {
      submitButtonDidJavaScriptRun.setAttribute("disabled", true);
     }, 500);
    }
    checkFormSubmittedDidJavaScriptFunctionRun();
    cleanTmpDidJavaScriptFunctionRun(1);
   </script>';
 }  else {
  /* ***********************************************************************************             */
  /* START - PHP AS NEEDED *************************************************************             */ 
  /* Blocks where JavaScript did not run.                                                            */
  
  /* CHANGE                                                                                          */
  /* echo "JavaScript did not run.";                                                                 */
  /* echo "";                                                                                        */
  
  
  /* ---- ----- ----- ----- 0 => JS DID __NOT__ RUN ---- REPLACE WITH YOUR PHP ----- ----- ----- --- */   
  
  
  /* END - PHP AS NEEDED ***************************************************************             */ 
  /* ***********************************************************************************             */  
 }
?>
 
```


## Source Files:

PHP in `example` was derived from [W3 Schools](https://www.w3schools.com/) page in [PHP form tutorial](https://www.w3schools.com/php/php_form_complete.asp).

