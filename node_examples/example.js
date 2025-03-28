// example.js

const express = require('express');
const app = express();
const port = 3001;

app.get('/', (req, res) => {
  res.send('Hello World!');
});

// New route to send academic programs data
app.get('/api/academicprograms', (req, res) => {
  const academicPrograms = [
    { id: 1, name: 'Computer Science', duration: '2 years' },
    { id: 2, name: 'Information Technology', duration: '3 years' },
    // Add more academic programs here
  ];
  res.json(academicPrograms);
});

app.listen(port, () => {
  console.log(`Example app listening on port ${port}`);
});
