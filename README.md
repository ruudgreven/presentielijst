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

or when you also want to add groups
```csv
id;lastname;insertion;firstname;group
123456;Greven;;Ruud;group1
34567;Tillaart;van der;Henk;group1
34568;Flupsel;van der;Henk;group2
```

Notice that the group will be added only once. So there are 2 groups after the above input.

### Output example
```json
{"status": "ok", "response":[
{"id":"123456","firstname":"Ruud","insertion":"","lastname":"Greven"},
{"id":"34567","firstname":"Henk","insertion":"van der","lastname":"Tillaart"}
]};
```


***
## GET /students.__format__
Returns a list of all students in the given format

### Supported formats
JSON and CSV

### Output example
```json
{"status": "ok", "response":[
{"id":"123456","firstname":"Ruud","insertion":"","lastnaam":"Greven"},
{"id":"34567","firstname":"Henk","insertion":"van der","lastnaam":"Tillaart"}
]};
```


***
## GET /students/__id__.__format__
Returns a list of the student with id __id__ in the given format

### Supported formats
JSON and CSV

### Output example
```json
{"status": "ok", "response":[
{"id":"123456","firstname":"Ruud","insertion":"","lastnaam":"Greven"}
]};
```


***
## GET /groups.__format__
Returns a list of groups

### Supported formats
JSON and CSV

### Output example
```json
{"status": "ok", "response":[
{"name":"EIN1Vp","owner_user_id":"0"},{"name":"EIN1Vq","owner_user_id":"0"}
]};
```

***
## GET /groups/__name__.__format__
Returns a list of all students in the given group in the given format

### Supported formats
JSON and CSV

### Output example
```json
{"status": "ok", "response":[
{"id":"123456","firstname":"Ruud","insertion":"","lastnaam":"Greven"},
{"id":"34567","firstname":"Henk","insertion":"van der","lastnaam":"Tillaart"}
]};
```



 
