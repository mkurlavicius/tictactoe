# Tic Tac Toe

- [x] Resolving winner
- [x] Setup
    
    The project is made using: 
    
    * Laravel PHP framework. 
    * Mysql database.
    * Apache web server.
    * Physical server as my personal computer attached to permanent ip - 212.76.255.185 and
    port 8080.  
    
- [x] Documentation

    The idea behind the code is to split the model domain into 4 model classes:
    
    * Player - playing the games.
    * Game - containing the squares and assigned to player.
    * Square - belonging to "Game", having coordinates.
    * Moves - belonging to "Game" and updating corresponding "Squares" on creation.
    
    And 2 "helper model" classes:
    
    * Computer - who analyses the game and makes a move. Currently its a simple version
    where computer chooses a random square.
    * Coordinate - for passing 'x' and 'y' of squares and moves around.
    
    The application flow is simple - Games (controller) have 3 actions, 
    Moves (controller) have 1 action.
    
    * Games@index - List of all your played games (and in progress). Players id is stored in session
    with lifetime of 1 year.
    * Games@create - Form to create game from to parameters (size and who starts). This action redirects
    to action "Show".
    * Games@show - Where the game is played. All the squares are displayed in a grid using coordinates.
    and each click on empty square, creates a new move.
    * Moves@create - Hidden form behind every button to create a new move on click and redirect back
    to Games@show action.
    
- [x] Unit Tests
