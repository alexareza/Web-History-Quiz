# Web-History-Quiz
A web history quiz that is graded on the server side using PHP, with the addition of HTML and CSS.
Users must log in for authentication.
The focus of this project was to practice using sessions, so I did not use any SQL databases. Results and user login information are stored in text files (I am aware this is not secure without a database!).
To ensure security, I had to prevent the same user from logging in twice and set a 15-minute time limit for completing the quiz.
If the user did not complete the quiz in 15 minutes, their score was recorded based on the correct answers they had submitted up to that point.
I made sure to validate my HTML and CSS and tested my page across multiple browsers.
