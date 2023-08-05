<?php

// Read the variables sent via POST from our API
$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];
$answers = "";

if ($text == "") {
    // This is the first request. Note how we start the response with CON
    $response  = "CON Welcome to Best Brains \n";
    $response .= "1. Primary School Competition \n";
    $response .= "2. Secondary School Competition \n";
   
} else if ($text == "1" || $text == "2") {
    // Logic for first level response
    $response = "CON Select Subject \n";
    $response .= "1. Maths \n";
    $response .= "2. English \n";
    $response .= "3. Science \n";
    $response .= "4. Basic Tech";

// SECOND LEVEL FOR 1. PRIMARY 
} else if($text == "1*1") { 
    // This is a second level response where the user selected PRIMARY and MATHEMATICS 
    $url = "https://applications.oes.com.ng/OESWebApp/ussdqp.do?level=CEE&subject=Mathematics";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    $result = curl_exec($ch);

    if ($result === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $json_data = json_decode($result, true);
        if ($json_data === null) {
            echo "JSON Error: " . json_last_error_msg();
        } else {

            $randomKeys = array_rand($json_data, 3);

            // Initialize empty arrays to store the data
            $ids = [];
            $questions = [];
            $instructions = [];
            $theOptionAs = [];
            $theOptionBs = [];
            $theOptionCs = [];
            $theOptionDs = [];
            $theOptionEs = [];
            $answertoquestions = [];
    
            // Extract data from the JSON array and store them in separate variables
            foreach ($randomKeys as $index) {
                $item = $json_data[$index];
                $ids[] = $item['id'];
                $questions[] = $item['question'];
                $instructions[] = $item['instruction'];
                $theOptionAs[] = $item['theOptionA'];
                $theOptionBs[] = $item['theOptionB'];
                $theOptionCs[] = $item['theOptionC'];
                $theOptionDs[] = $item['theOptionD'];
                $theOptionEs[] = $item['theOptionE'];
                $answertoquestions[] = $item['answertoquestion'];
            }
            
            $response = "CON Input/SUBMIT your Answers & State of Residence accordingly separated by comma \n";

    
            // Now, you can use the variables in your desired way, such as displaying the questions with their options
            foreach ($questions as $index => $question) {
                $sn = $index + 1;
                $response .= "----------------------------------<br>";
                $response .= "$sn. {$question} <br>";
                $response .= "{$theOptionAs[$index]}  |  ";
                $response .= "{$theOptionBs[$index]}  |  ";
                $response .= "{$theOptionCs[$index]}  |  ";
                $response .= "{$theOptionDs[$index]}  |  ";
                $response .= "{$theOptionEs[$index]} <br>";
            }
    
            // Store all the answers in a single array
            $answers_array = array($answertoquestions);
    
            $answer1 = preg_replace('/[^a-zA-Z]/', '', $answers_array[0][0]);
            $answer2 = preg_replace('/[^a-zA-Z]/', '', $answers_array[0][1]);
            $answer3 = preg_replace('/[^a-zA-Z]/', '', $answers_array[0][2]);
    
            $a1 = substr($answer1, 0, 1);
            $a2 = substr($answer2, 0, 1);
            $a3 = substr($answer3, 0, 1);
    
            $answers = $a1.$a2.$a3;
        
    }

    }
    

} else if($text == "1*2") { 

$url = "https://applications.oes.com.ng/OESWebApp/ussdqp.do?level=CEE&subject=English";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
$result = curl_exec($ch);

if ($result === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    $json_data = json_decode($result, true);
    if ($json_data === null) {
        echo "JSON Error: " . json_last_error_msg();
    } else {

        // Filter questions to exclude those containing the word "passage"
        $filtered_questions = array_filter($json_data, function ($item) {
            return strpos(strtolower($item['instruction']), 'passage') === false;
        });

        $randomKeys = array_rand($filtered_questions, 2);

        // Initialize empty arrays to store the data
        $ids = [];
        $questions = [];
        $instructions = [];
        $theOptionAs = [];
        $theOptionBs = [];
        $theOptionCs = [];
        $theOptionDs = [];
        $theOptionEs = [];
        $answertoquestions = [];

        // Extract data from the filtered JSON array and store them in separate variables
        foreach ($randomKeys as $index) {
            $item = $filtered_questions[$index];
            $ids[] = $item['id'];
            $questions[] = $item['question'];
            $instructions[] = $item['instruction'];
            $theOptionAs[] = $item['theOptionA'];
            $theOptionBs[] = $item['theOptionB'];
            $theOptionCs[] = $item['theOptionC'];
            $theOptionDs[] = $item['theOptionD'];
            $theOptionEs[] = $item['theOptionE'];
            $answertoquestions[] = $item['answertoquestion'];
        }

        $response = "CON Input/SUBMIT your Answers & State of Residence accordingly separated by comma \n";

        // Now, you can use the variables in your desired way, such as displaying the questions with their options
        foreach ($questions as $index => $question) {
            $sn = $index + 1;
            $response .= "----------------------------------<br>";
            $response .= "$sn. {$instructions[$index]}. ";
            $response .= "{$question} <br>";
            $response .= "{$theOptionAs[$index]}  |  ";
            $response .= "{$theOptionBs[$index]}  |  ";
            $response .= "{$theOptionCs[$index]}  |  ";
            $response .= "{$theOptionDs[$index]}  |  ";
            $response .= "{$theOptionEs[$index]} <br>";
        }

        // Store all the answers in a single array
        $answers_array = array($answertoquestions);

        $answer1 = preg_replace('/[^a-zA-Z]/', '', $answers_array[0][0]);
        $answer2 = preg_replace('/[^a-zA-Z]/', '', $answers_array[0][1]);
        $answer3 = preg_replace('/[^a-zA-Z]/', '', $answers_array[0][2]);

        $a1 = substr($answer1, 0, 1);
        $a2 = substr($answer2, 0, 1);
        $a3 = substr($answer3, 0, 1);

        $answers = $a1.$a2.$a3;
            
    }

}
curl_close($ch);

} else if($text == "1*3") { 
    // This is a second level response where the user selected PRIMARY and SCIENCE 
    $url = "https://applications.oes.com.ng/OESWebApp/ussdqp.do?level=CEE&subject=Science";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    $result = curl_exec($ch);

    if ($result === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $json_data = json_decode($result, true);
        if ($json_data === null) {
            echo "JSON Error: " . json_last_error_msg();
        } else {

    $randomKeys = array_rand($json_data, 3);

    $ids = [];
    $questions = [];
    $instructions = [];
    $theOptionAs = [];
    $theOptionBs = [];
    $theOptionCs = [];
    $theOptionDs = [];
    $theOptionEs = [];
    $answertoquestions = [];

    foreach ($randomKeys as $index) {
        $item = $json_data[$index];
        $ids[] = $item['id'];
        $questions[] = $item['question'];
        $instructions[] = $item['instruction'];
        $theOptionAs[] = $item['theOptionA'];
        $theOptionBs[] = $item['theOptionB'];
        $theOptionCs[] = $item['theOptionC'];
        $theOptionDs[] = $item['theOptionD'];
        $theOptionEs[] = $item['theOptionE'];
        $answertoquestions[] = $item['answertoquestion'];
    }

    $response = "CON Input/SUBMIT your Answers & State of Residence accordingly separated by comma \n";

    foreach ($questions as $index => $question) {
        $sn = $index + 1;
        $response .= "----------------------------------<br>";
        $response .= "$sn. {$question} <br>";
        $response .= "{$theOptionAs[$index]}  |  ";
        $response .= "{$theOptionBs[$index]}  |  ";
        $response .= "{$theOptionCs[$index]}  |  ";
        $response .= "{$theOptionDs[$index]}  |  ";
        $response .= "{$theOptionEs[$index]} <br>";
    }

    }
    }
    curl_close($ch);

} else if($text == "1*4") { 
    // This is a second level response where the user selected PRIMARY and BASIC TECH 
    $url = "https://applications.oes.com.ng/OESWebApp/ussdqp.do?level=CEE&subject=Basictech";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    $result = curl_exec($ch);

    if ($result === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $json_data = json_decode($result, true);
        if ($json_data === null) {
            echo "JSON Error: " . json_last_error_msg();
        } else {

    $randomKeys = array_rand($json_data, 3);

    $ids = [];
    $questions = [];
    $instructions = [];
    $theOptionAs = [];
    $theOptionBs = [];
    $theOptionCs = [];
    $theOptionDs = [];
    $theOptionEs = [];
    $answertoquestions = [];

    foreach ($randomKeys as $index) {
        $item = $json_data[$index];
        $ids[] = $item['id'];
        $questions[] = $item['question'];
        $instructions[] = $item['instruction'];
        $theOptionAs[] = $item['theOptionA'];
        $theOptionBs[] = $item['theOptionB'];
        $theOptionCs[] = $item['theOptionC'];
        $theOptionDs[] = $item['theOptionD'];
        $theOptionEs[] = $item['theOptionE'];
        $answertoquestions[] = $item['answertoquestion'];
    }

    $response = "CON Input/SUBMIT your Answers & State of Residence accordingly separated by comma \n";

    foreach ($questions as $index => $question) {
        $sn = $index + 1;
        $response .= "----------------------------------<br>";
        $response .= "$sn. {$question} <br>";
        $response .= "{$theOptionAs[$index]}  |  ";
        $response .= "{$theOptionBs[$index]}  |  ";
        $response .= "{$theOptionCs[$index]}  |  ";
        $response .= "{$theOptionDs[$index]}  |  ";
        $response .= "{$theOptionEs[$index]} <br>";
    }

    }
    }
    curl_close($ch);

// SECOND LEVEL FOR 2. SECONDARY
} else if($text == "2*1") { 
    // This is a second level response where the user selected SECONDARY and MATHEMATICS 
    $url = "https://applications.oes.com.ng/OESWebApp/ussdqp.do?level=JSCE&subject=Mathematics";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    $result = curl_exec($ch);

    if ($result === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $json_data = json_decode($result, true);
        if ($json_data === null) {
            echo "JSON Error: " . json_last_error_msg();
        } else {

    $randomKeys = array_rand($json_data, 3);

    $ids = [];
    $questions = [];
    $instructions = [];
    $theOptionAs = [];
    $theOptionBs = [];
    $theOptionCs = [];
    $theOptionDs = [];
    $theOptionEs = [];
    $answertoquestions = [];

    foreach ($randomKeys as $index) {
        $item = $json_data[$index];
        $ids[] = $item['id'];
        $questions[] = $item['question'];
        $instructions[] = $item['instruction'];
        $theOptionAs[] = $item['theOptionA'];
        $theOptionBs[] = $item['theOptionB'];
        $theOptionCs[] = $item['theOptionC'];
        $theOptionDs[] = $item['theOptionD'];
        $theOptionEs[] = $item['theOptionE'];
        $answertoquestions[] = $item['answertoquestion'];
    }

    $response = "CON Input/SUBMIT your Answers & State of Residence accordingly separated by comma \n";

    foreach ($questions as $index => $question) {
        $sn = $index + 1;
        $response .= "----------------------------------<br>";
        $response .= "$sn. {$question} <br>";
        $response .= "{$theOptionAs[$index]}  |  ";
        $response .= "{$theOptionBs[$index]}  |  ";
        $response .= "{$theOptionCs[$index]}  |  ";
        $response .= "{$theOptionDs[$index]}  |  ";
        $response .= "{$theOptionEs[$index]} <br>";
    }

    }
    }
    curl_close($ch);

} else if($text == "2*2") { 
    // This is a second level response where the user selected SECONDARY and ENGLISH 
    $url = "https://applications.oes.com.ng/OESWebApp/ussdqp.do?level=JSCE&subject=English";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    $result = curl_exec($ch);

    if ($result === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $json_data = json_decode($result, true);
        if ($json_data === null) {
            echo "JSON Error: " . json_last_error_msg();
        } else {

    $randomKeys = array_rand($json_data, 3);

    $ids = [];
    $questions = [];
    $instructions = [];
    $theOptionAs = [];
    $theOptionBs = [];
    $theOptionCs = [];
    $theOptionDs = [];
    $theOptionEs = [];
    $answertoquestions = [];

    foreach ($randomKeys as $index) {
        $item = $json_data[$index];
        $ids[] = $item['id'];
        $questions[] = $item['question'];
        $instructions[] = $item['instruction'];
        $theOptionAs[] = $item['theOptionA'];
        $theOptionBs[] = $item['theOptionB'];
        $theOptionCs[] = $item['theOptionC'];
        $theOptionDs[] = $item['theOptionD'];
        $theOptionEs[] = $item['theOptionE'];
        $answertoquestions[] = $item['answertoquestion'];
    }

    $response = "CON Input/SUBMIT your Answers & State of Residence accordingly separated by comma \n";

    foreach ($questions as $index => $question) {
        $sn = $index + 1;
        $response .= "----------------------------------<br>";
        $response .= "$sn. {$question} <br>";
        $response .= "{$theOptionAs[$index]}  |  ";
        $response .= "{$theOptionBs[$index]}  |  ";
        $response .= "{$theOptionCs[$index]}  |  ";
        $response .= "{$theOptionDs[$index]}  |  ";
        $response .= "{$theOptionEs[$index]} <br>";
    }

    }
    }
    curl_close($ch);


} else if($text == "2*3") { 
    // This is a second level response where the user selected SECONDARY and SCIENCE 
    $url = "https://applications.oes.com.ng/OESWebApp/ussdqp.do?level=JSCE&subject=Science";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    $result = curl_exec($ch);

    if ($result === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $json_data = json_decode($result, true);
        if ($json_data === null) {
            echo "JSON Error: " . json_last_error_msg();
        } else {

    $randomKeys = array_rand($json_data, 3);

    $ids = [];
    $questions = [];
    $instructions = [];
    $theOptionAs = [];
    $theOptionBs = [];
    $theOptionCs = [];
    $theOptionDs = [];
    $theOptionEs = [];
    $answertoquestions = [];

    foreach ($randomKeys as $index) {
        $item = $json_data[$index];
        $ids[] = $item['id'];
        $questions[] = $item['question'];
        $instructions[] = $item['instruction'];
        $theOptionAs[] = $item['theOptionA'];
        $theOptionBs[] = $item['theOptionB'];
        $theOptionCs[] = $item['theOptionC'];
        $theOptionDs[] = $item['theOptionD'];
        $theOptionEs[] = $item['theOptionE'];
        $answertoquestions[] = $item['answertoquestion'];
    }

    $response = "CON Input/SUBMIT your Answers & State of Residence accordingly separated by comma \n";

    foreach ($questions as $index => $question) {
        $sn = $index + 1;
        $response .= "----------------------------------<br>";
        $response .= "$sn. {$question} <br>";
        $response .= "{$theOptionAs[$index]}  |  ";
        $response .= "{$theOptionBs[$index]}  |  ";
        $response .= "{$theOptionCs[$index]}  |  ";
        $response .= "{$theOptionDs[$index]}  |  ";
        $response .= "{$theOptionEs[$index]} <br>";
    }

    }
    }
    curl_close($ch);


} else if($text == "2*4") { 
    // This is a second level response where the user selected SECONDARY and BASIC TECH 
    $url = "https://applications.oes.com.ng/OESWebApp/ussdqp.do?level=JSCE&subject=Basictech";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    $result = curl_exec($ch);

    if ($result === false) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $json_data = json_decode($result, true);
        if ($json_data === null) {
            echo "JSON Error: " . json_last_error_msg();
        } else {

    $randomKeys = array_rand($json_data, 3);

    $ids = [];
    $questions = [];
    $instructions = [];
    $theOptionAs = [];
    $theOptionBs = [];
    $theOptionCs = [];
    $theOptionDs = [];
    $theOptionEs = [];
    $answertoquestions = [];

    foreach ($randomKeys as $index) {
        $item = $json_data[$index];
        $ids[] = $item['id'];
        $questions[] = $item['question'];
        $instructions[] = $item['instruction'];
        $theOptionAs[] = $item['theOptionA'];
        $theOptionBs[] = $item['theOptionB'];
        $theOptionCs[] = $item['theOptionC'];
        $theOptionDs[] = $item['theOptionD'];
        $theOptionEs[] = $item['theOptionE'];
        $answertoquestions[] = $item['answertoquestion'];
    }

    $response = "CON Input/SUBMIT your Answers & State of Residence accordingly separated by comma \n";

    foreach ($questions as $index => $question) {
        $sn = $index + 1;
        $response .= "----------------------------------<br>";
        $response .= "$sn. {$question} <br>";
        $response .= "{$theOptionAs[$index]}  |  ";
        $response .= "{$theOptionBs[$index]}  |  ";
        $response .= "{$theOptionCs[$index]}  |  ";
        $response .= "{$theOptionDs[$index]}  |  ";
        $response .= "{$theOptionEs[$index]} <br>";
    }

    }
    }
    curl_close($ch);


// THIRD LEVEL RESPONSE

} else if (strpos($text, "1*1*") !== false || strpos($text, "1*2*") !== false || strpos($text, "1*3*") !== false || strpos($text, "1*4*") !== false || strpos($text, "2*1*") !== false || strpos($text, "2*2*") !== false || strpos($text, "2*3*") !== false || strpos($text, "2*4*") !== false) {
    // This is the third level response where the user submitted answers and state
    //    processSubmission($text);

    $first = substr($text, 0, 4);

    // Extract the rest of the characters from position 4 onwards and store it in another variable
    $rest = substr($text, 4);

    // Remove white spaces from the rest string
    $rest = str_replace(' ', '', $rest);

    // Split the input text using commas as the separator
    
    $restParts = explode(",", $rest);

    // Check if there are exactly four parts in the input
    if (count($restParts) == 4) {
        // Extract the answers and state from the input
        $answer1 = trim($restParts[0]);
        $answer2 = trim($restParts[1]);
        $answer3 = trim($restParts[2]);
        $answer4 = trim($restParts[3]);

        // Check if all answers are either 'a', 'b', or 'c'
        if (
            ($answer1 === 'a' || $answer1 === 'b' || $answer1 === 'c' || $answer1 === 'd' || $answer1 === 'e') &&
            ($answer2 === 'a' || $answer2 === 'b' || $answer2 === 'c' || $answer2 === 'd' || $answer2 === 'e') &&
            ($answer3 === 'a' || $answer3 === 'b' || $answer3 === 'c' || $answer3 === 'd' || $answer3 === 'e') &&
            (is_string($answer4))
        ) {
            // Extract the state from the input
            $state = $answer4;

            // Now you can use the answers and state as needed in your code
            // For example, you can store them in variables, insert into a database, or perform other operations.

            // Your code here...

            // Send a response back to the user
            $response = "END Thank you for your submission. Your Answers are $answer1,$answer2,$answer3 and Your State is $state.";
        } else {
            // If any of the answers is not 'a', 'b', or 'c', send an error response
            $response = "END Invalid input format. Please try again and provide answers in the format 'a, b, c, state'.";
        }
    } else {
        // If the input does not have exactly four parts, send an error response
        $response = "END Invalid input format. Please try again and provide answers in the format 'a, b, c, state'.";
    }
 
} else {
    // If the user enters an invalid response at any level, send an error response
    $response = "END Invalid response. Please enter a valid option.";
}
curl_close($ch);
// Echo the response back to the API
header('Content-type: text/plain');
echo $response;
?>
