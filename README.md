# Presentielijst
Een presentielijst tooltje

# API

## POST /students.json
Upload a list of students in CSV format. Adds them to the database and receives the added students back in JSON format.

### Input parameters
File should be uploaded as Form File in the POST message

### Input file example
```csv
id;lastname;insertion;firstname;
123456;Greven;;Ruud;
34567;Tillaart;van der;Henk;
```

### Output example
```json
{"status": "ok", "response":[
{"id":"123456","firstname":"Ruud","insertion":"","lastnaam":"Greeven"},
{"id":"34567","firstname":"Henk","insertion":"van der","lastnaam":"Tillaart"}
];
```


***
## GET /students.__format__
Returns a list of all students in the given format

### Supported formats
JSON and CSV

### Output example
```json
{"status": "ok", "response":[
{"id":"123456","firstname":"Ruud","insertion":"","lastnaam":"Greeven"},
{"id":"34567","firstname":"Henk","insertion":"van der","lastnaam":"Tillaart"}
];
```


***
## GET /students/__id__.__format__
Returns a list of the student with id __id__ in the given format

### Supported formats
JSON and CSV

### Output example
```json
{"status": "ok", "response":[
{"id":"123456","firstname":"Ruud","insertion":"","lastnaam":"Greeven"}
];
```

 
