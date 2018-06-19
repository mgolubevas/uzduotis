<?php
class userRecord {
	public $fname;    //first name
	public $lname;    //last name
	public $email;    //email
	public $phone1;   //phone 1
	public $phone2;   //phone 2
	public $rcomment; //user comment
	
	function __construct($fname_par,$lname_par,$email_par,$phone1_par,$phone2_par,$rcomment_par){
		$this->fname = $fname_par;
		$this->lname = $lname_par;
		$this->email = $email_par;
		$this->phone1 = $phone1_par;
		$this->phone2 = $phone2_par;
		$this->rcomment = $rcomment_par;
	}
	
	public function editVar($name,$value){
		$this->$name = $value;
	}
	
	public function hasEmail($object){
		if (($this->email)==$object){
			return true;
		} else {
			return false;
		}
	}
	
	public function outputLine(){
		return $this->fname." ".$this->lname." ".$this->email." ".$this->phone1." ".$this->phone2." ".$this->rcomment."\n";
	}
}

function userInput($parameter = 0){
	global $userList;
	switch($parameter){
	case "0":
		echo "Welcome to the registration system\n"; 
		echo "Type 'add' to add a new record\n"; 
		echo "Type 'edit' to edit a record\n"; 
		echo "Type 'delete' to delete a record\n";
		echo "Type 'loadcsv' to load a .csv file\n";
		echo "Type 'exit' to quit the program\n";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		return trim($line);
		break;
	case "1":
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$line = trim($line);
		if ($line == "quit"){
			mainMenu(userInput(0));
		}elseif (is_numeric($line)){
			return $line;
			break;
		} else {
			echo "You didn't type a number, try again\n";
			userInput($parameter);
			break;
		}
	case "2":
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$line = trim($line);
		if (preg_match('/\s/',$line)){
			echo "You've typed the word with a whitespace, please try again\n";
			return userInput($parameter);
			break;
		} else {
			return $line;
			break;
		}
	case "3":
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$line = trim($line);
		$line = filter_var($line,FILTER_SANITIZE_EMAIL);
		$result =0;
		if (filter_var($line, FILTER_VALIDATE_EMAIL)) {
			for ($z = 0; $z < count($userList);$z++){
				if($userList[$z]->hasEmail($line)==1)
					$result++;
			}
			if ($result>0){
				echo "Email already is in use, please use another email\n";
				return userInput($parameter);
				break;
			} else {
				return $line;
				break;
			}
		} else {
			echo "Please enter a valid email\n";
			return userInput($parameter);
			break;
		}
	case "4":
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$line = trim($line);
		if (strtolower($line)!=="none"){
			$line = preg_replace("%[^0-9]%", "", $line);
			$length = strlen($line);
			if ( $length == 11 || $length == 9 ) {
				return $line;
				break;
			} else {
				echo "Wrong phone number, pleace try again\n";
				return userInput($parameter);
				break;
			}
		} else {
			return $line="None";
			break;
		}
	case "5":
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$line = trim($line);
		if (preg_match('/\s/',$line)){
			$line = str_replace(' ', '', $line);
			return $line;
			break;
		} else {
			return $line;
			break;
		}
	default:
		echo "Something went wrong try again\n";
		userInput(0);
	}
}


function mainMenu($selector){
	switch($selector){
    case "add":
		echo "You've have chosen to add a new record\n";
		addRecord();
		break;
	case "edit":
		echo "You've chosen to edit a record\n";
		editRecord();
		break;
	case "loadcsv":
		echo "You've chosen to load .csv file\n";
		loadCSV();
		break;
	case "delete":
		echo "You've chosen to delete a record\n";
		deleteRecord();
		break;
	case "exit":
		exitProgram();
		echo "You have closed the program\n";
		exit;
		break;
	default:
		echo "You've typed something wrong\n";
		mainMenu(userInput(0));
	}
}

function addRecord(){
	global $userList,$i;
	listRecords($userList,$i);
	$userList[$i] = getRecord();
	echo "you have added a record\n \n";
	$i++;
	listRecords($userList,$i);
	mainMenu(userInput(0));
}
function editRecord(){
	global $userList,$i;
	listRecords($userList,$i);
	echo "Please enter the list number of the record you want to edit\n";
	echo "Type 'quit' to go back to main menu: ";
	$temp = userInput(1)-1;
	echo "Press 1 to completely rewrite the record and press 2 to edit a field in the record\n";
	echo "Type 'quit' to exit: ";
	editRecordChoice($temp);
	listRecords($userList,$i);
	echo "you have edited a record\n \n";
	mainMenu(userInput(0));
}
function deleteRecord(){
	global $userList,$i;
	listRecords($userList,$i);
	echo "Please enter the list number of the record you want to delete\n";
	echo "Type 'quit' to go to the main menu: ";
	$input = userInput(1);
	$input--;
	unset($userList[$input]);
	$i--;
	listRecords($userList,$i);
	mainMenu(userInput(0));
}
function listRecords($array,$y){
	$lineNumber =1;
	echo "\n";
	for ($z = 0; $z < $y;$z++){
		echo $lineNumber." ".$array[$z]->outputLine();
		$lineNumber++;
	}
	echo "\n";
}
function getRecord(){
	echo "Enter first name: ";
	$temp_fname = userInput(2);
	echo "Enter last name: ";
	$temp_lname = userInput(2);
	echo "Enter email: ";
	$temp_email = userInput(3);
	echo "Enter phone 1 (if you dont have one enter 'None'): ";
	$temp_phone1 = userInput(4);
	echo "Enter phone 2 (if you dont have one enter 'None'): ";
	$temp_phone2 = userInput(4);
	echo "Enter comment: ";
	$temp_comment = userInput(5);
	return (new userRecord($temp_fname, $temp_lname, $temp_email, $temp_phone1, $temp_phone2, $temp_comment));
}

function editRecordChoice($choice){
	global $userList;
	$temp = userInput(0);
	switch($temp){
		case "1":
			$userList[$choice] = getRecord();
			break;
		case "2":
			editField($choice);
			break;
		case "quit":
			mainMenu(userInput(0));
			break;
		default:
			echo "\nYou've typed something wrong, try again\n";
			echo "Press 1 to completely rewrite the record and press 2 to edit a field in the record: ";
			editRecordChoice($choice);
	}
}

function editField($choice){
	global $userList;
	echo "Enter 'first' to edit first name; Enter 'last' to edit last name;\n";
	echo "Enter 'email' to edit email; Enter 'phone1' to edit Phone 1;\n";
	echo "Enter 'phone2' to edit Phone 2; Enter 'comment' to edit comment\n";
	echo "Enter 'quit' to go to the main menu\n";
	$temp = userInput(0);
	switch($temp){
		case "first":
			echo "Enter new first name: ";
			$userList[$choice]->editVar("fname",userInput(2));
			break;
		case "last":
			echo "Enter new last name: ";
			$userList[$choice]->editVar("lname",userInput(2));
			break;
		case "email":
			echo "Enter new email: ";
			$userList[$choice]->editVar("email",userInput(3));
			break;
		case "phone1":
			echo "Enter new Phone 1 (if you dont have one enter 'None'): ";
			$userList[$choice]->editVar("phone1",userInput(4));
			break;
		case "phone2":
			echo "Enter new Phone 2 (if you dont have one enter 'None'): ";
			$userList[$choice]->editVar("phone2",userInput(4));
			break;
		case "comment":
			echo "Enter new comment: ";
			$userList[$choice]->editVar("rcomment",userInput(5));
			break;
		case "quit":
			mainMenu(userInput(0));
			break;
		default:
			echo "you typed something wrong\n";
			editField($choice);
	}
}

function loadCSV(){
	global $userList,$i;
	echo "Please enter a the .csv file name\n";
	echo "Notice: the file must be in the same folder as the script: ";
	$filename = userInput(2).".csv";
	$filecsv = fopen($filename, 'r');
	if (!$filecsv) {
		echo "<p>Unable to open remote file.\n";
		mainMenu(userInput(0));
    } else {
		if (filesize($filename)==0){
			$userList[$i] = new userRecord("Vardenis", "Pavardenis", "varpar@gmail.com", "123456", "123456", "dummyline");
			$i++;
		} else {
			while (($userinfo = fgetcsv($filecsv, 1000, ","))!== FALSE ){
				list ($firstname, $lastname, $email, $phonenumber1, $phonenumber2, $comment) = $userinfo;
				$userList[$i] = new userRecord($firstname, $lastname, $email, $phonenumber1, $phonenumber2, $comment);
				$i++;
			}
			echo ".CSV file has been loaded\n\n";
			listRecords($userList,$i);
		}
	}
	mainMenu(userInput(0));
}

function exitProgram(){
	global $file, $userList;
	$output = fopen('client.txt.tmp','a+');
	
	for ($o = 0; $o <count($userList) ; $o++){
		fwrite($output, $userList[$o]->outputLine());
	}
	fclose($file);fclose($output);
	rename('client.txt.tmp','client.txt');
}

$file = fopen('client.txt', 'a+');
if (!$file) {
    echo "<p>Unable to open remote file.\n";
    exit;
}
$i=0;
if (filesize('client.txt')==0){
	$userList[$i] = new userRecord("Vardenis", "Pavardenis", "varpar@gmail.com", "123456", "123456", "dummyline");
	$i++;
} else {
	while (($userinfo = fgetcsv($file, 1000, " "))!== FALSE ){
		list ($firstname, $lastname, $email, $phonenumber1, $phonenumber2, $comment) = $userinfo;
		$userList[$i] = new userRecord($firstname, $lastname, $email, $phonenumber1, $phonenumber2, $comment);
		$i++;
	}
}
mainMenu(userInput(0));
?>