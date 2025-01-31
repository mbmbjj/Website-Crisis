<!DOCTYPE html>
<html>
<head>
    <title>ML Model API Interface</title>
</head>
<body>
    <h2>1) Send Text Data to ML API</h2>
    <input type="text" id="input_data" placeholder="Enter data">
    <button onclick="sendData()">Send</button>
    <p id="result"></p>

    <hr>

    <h2>2) Upload File</h2>
    <input type="file" id="upload_file">
    <button onclick="uploadFile()">Upload</button>
    <p id="upload_result"></p>

    <hr>

    <h2>3) Download File</h2>
    <input type="text" id="download_file_id" placeholder="Enter file_id to download">
    <button onclick="downloadFile()">Download</button>
    <p>Check your browser's downloads if successful.</p>

    <script>
        function sendData() {
            let input = document.getElementById("input_data").value;

            fetch("api.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "input_data=" + encodeURIComponent(input)
            })
            .then(response => response.text())
            .then(data => {
                console.log("Raw API Response:", data);
                try {
                    let jsonData = JSON.parse(data);
                    document.getElementById("result").innerText = 
                        "Response: " + JSON.stringify(jsonData);
                } catch (error) {
                    console.error("JSON Parsing Error:", error);
                    document.getElementById("result").innerText = 
                        "Invalid JSON response. Check console.";
                }
            })
            .catch(error => console.error("Fetch Error:", error));
        }

        function uploadFile() {
            let fileInput = document.getElementById("upload_file");
            if (fileInput.files.length === 0) {
                alert("Please select a file first.");
                return;
            }

            let formData = new FormData();
            formData.append("file", fileInput.files[0]);

            fetch("upload_file.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log("Upload Raw Response:", data);
                try {
                    let jsonData = JSON.parse(data);
                    if (jsonData.error) {
                        document.getElementById("upload_result").innerText = 
                            "Error: " + jsonData.error;
                    } else {
                        document.getElementById("upload_result").innerText = 
                            "Uploaded! File ID: " + jsonData.file_id;
                    }
                } catch (err) {
                    console.error("Parsing error:", err);
                    document.getElementById("upload_result").innerText = 
                        "Error parsing upload response.";
                }
            })
            .catch(err => {
                console.error("Fetch Error:", err);
                document.getElementById("upload_result").innerText = 
                    "Upload failed. Check console.";
            });
        }

        function downloadFile() {
            let fileId = document.getElementById("download_file_id").value.trim();
            if (!fileId) {
                alert("Please provide a valid file_id.");
                return;
            }
            // We'll just open the PHP route in a new window (simplest approach)
            window.open("download_file.php?file_id=" + fileId, "_blank");
        }
    </script>
</body>
</html>
