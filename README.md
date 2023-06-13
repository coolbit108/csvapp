# ProcessCSV Command - Docker Usage

This repository contains a Laravel `ProcessCSV` command that processes a CSV file and splits the names into individual person records.

## Prerequisites

- Docker: Ensure that Docker is installed on your system.

## Usage

1. Clone this repository to your local machine:
   ``git clone <repository_url> ``

2. Navigate to the cloned repository.
`` cd <repository_directory> ``
3. Run composer install.

4. Copy your CSV file to the repository directory. 
5. Build the Docker image using the provided Dockerfile. Run the following command from the repository directory:
   `` docker build -t mylaravelapp . ``
6. Once the Docker image is built, you can run a container and execute the ProcessCSV command using the following command:
``docker run -it --rm -v $(pwd):/var/www/html mylaravelapp php artisan csv:process <file_name.csv>
   ``

7. Replace <file_name.csv> with the name of your CSV file.
The command mounts the current directory ($(pwd)) into the container at /var/www/html and runs the ProcessCSV command on the specified CSV file.
The output will be displayed in the terminal.

##TESTS

1.To run the PHPUnit tests inside the Docker container, execute the following command:
```docker run -it --rm -v $(pwd):/var/www/html mylaravelapp /bin/bash```
2.Inside the container, navigate to the Laravel project directory:
``cd /var/www/html``
3.Run the PHPUnit tests using the php artisan test command:
``php artisan test``
# csvapp
