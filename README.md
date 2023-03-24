Backend Homework Assignment
This is a program that calculates shipment discounts based on specific rules. It takes input data from a file, and outputs the transactions with the reduced shipment price and shipment discount. The program is designed to be flexible, allowing for easy modification of existing rules and the addition of new ones.

Code Philosophy
This application follows a clean and simple code philosophy that is covered with unit tests and easy to maintain. Consistency and following language code style are also important. While it is recommended to follow the style guide of the chosen language, the code should be consistent in its formatting and follow standard coding practices. The program should be designed to be flexible, allowing for easy modification of existing rules and the addition of new ones.

Requirements
The requirements for this program are as follows:

Pick any programming language of your choice.
The code should follow the code philosophy described above.
Using additional libraries is prohibited, except for unit tests and build.
The program should have an easy way to start and run tests.
The program should have a short documentation of design decisions and assumptions in the code.
Input data should be loaded from a file (default name 'input.txt' is assumed).
The solution should output data to the screen (STDOUT) in a specific format.
The program design should be flexible enough to allow adding new rules and modifying existing ones easily.
Problem
Our application provides various shipping options to its members in France. Packages are assigned package sizes (S, M, or L) depending on their size, and can be shipped using either 'Mondial Relay' (MR) or 'La Poste' (LP).

The program's task is to create a shipment discount calculation module that implements specific rules:

All S shipments should always match the lowest S package price among the providers.
The third L shipment via LP should be free, but only once a calendar month.
Accumulated discounts cannot exceed 10 € in a calendar month. If there are not enough funds to fully cover a discount this calendar month, it should be covered partially.
The program takes input data from a file called 'input.txt'. Each line contains a date (in ISO format, without hours), package size code, and carrier code, separated by whitespace. The program outputs transactions with the reduced shipment price and shipment discount. If a line has an unrecognized carrier or size, or if the line format is incorrect, the program appends 'Ignored'.

How to Use
To use this program, follow these steps:

Clone this repository.
Open a terminal window and navigate to the project directory.
Run the program by typing ./program_name input.txt (replace 'program_name' with the name of your program).
The program will output the transactions with the reduced shipment price and shipment discount.
To run tests, type ./program_name test.
Design Decisions
Design decisions and assumptions are documented in the code itself. The program was designed to be flexible and easily modifiable, so that new rules and modifications can be added with ease.

Conclusion
This program calculates shipment discounts based on specific rules, and is designed to be flexible and easily modifiable. The program takes input data from a file and outputs transactions with the reduced shipment price and shipment discount. If a line has an unrecognized carrier or size, or if the line format is incorrect, the program appends 'Ignored'.