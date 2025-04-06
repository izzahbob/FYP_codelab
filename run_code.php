<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $code = $data['code'];

    // Save the Java code to a temporary file
    $file_path = "TempProgram.java";
    file_put_contents($file_path, $code);

    // Compile the Java program
    $compile_output = shell_exec("javac $file_path 2>&1");

    if ($compile_output) {
        echo json_encode(["output" => "Compilation Error:\n" . $compile_output]);
    } else {
        // Run the compiled Java program
        $run_output = shell_exec("java TempProgram 2>&1");
        echo json_encode(["output" => $run_output]);
    }
}
?>
