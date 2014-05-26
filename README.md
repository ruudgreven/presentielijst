# Presentielijst
Een presentielijst tooltje

# API

## POST /students.json
Upload a list of students in CSV format. Adds them to the database and receives the added students back in json

### Input parameters
File should be uploaded as Form File in the POST message

### Input example
```csv
id;lastname;insertion;firstname;
123456;Greven;;Ruud;
34567;Tillaart;van der;Henk;
```

### Output example
```json
{"status": "ok", "response":[
{"id":"123456","firstname":"Ruud","insertion":"","lastnaam":"Greeven"},{"id":"34567","firstname":"Henk","insertion":"van der","lastnaam":"Tillaart"}
];
```

 
