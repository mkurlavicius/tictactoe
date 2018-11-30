# Tic Tac Toe

- [x] The Game Itself
    
    * [Link To The App](http://212.76.255.185:8080)
    
- [x] Setup
    
    The project is made using: 
    
    * Laravel PHP framework. 
    * Mysql database.
    * Apache web server.
    * Bootstrap
    * Physical personal server attached to permanent public ip  - 212.76.255.185 and
      listening on port 8080. There is also some network trickery, the port is forwarded 
      from internet provider's router to home network routers, and those to 
      to the exact machine (server) on the network.   
    
- [x] Documentation

    The idea behind the code is to split the model domain into 4 model classes:
    
    * Player - playing the games.
    * Game - containing the squares and assigned to player.
    * Square - belonging to "Game", having coordinates.
    * Moves - belonging to "Game" and updating corresponding "Squares" on creation.
    
    And 3 "helper model" classes:
    
    * Computer - who analyses the game and makes a move. Currently its a simple version
    where computer chooses a random square.
    * Line - for analyzing winning conditions and who is winning (loosing)
    * Coordinate - for passing 'x' and 'y' of 'squares' and 'moves' around.
    
    The application flow is simple - GamesController has 3 actions, 
    MovesController have 1 action.
    
    * Games@index - List of all your played games (and in progress). Players id is stored in session
    with lifetime of 1 year.
    
    * Games@create - Form to create game from to parameters (size and who starts). This action redirects
    to action "Show".
    
    * Games@show - Where the game is played. All the squares are displayed in a grid using coordinates.
    and each click on empty square, creates a new move.
    
    * Moves@create - Hidden form behind every button to create a new move on click and redirect back
    to Games@show action.
    
- [x] API
    
    As application itself, the API is the same actions 
    
    * Api\Games@index - List all games, paginated. 
        
    * Api\Games@show  - The game that is played. Json includes squares and moves.
    
    * Api\Games@store - For creating the game. Size and who starts (computer, human) are the
    parameters. Returns the 'message', the 'game' and the 'last_move' in case computer
    started the game.
        
    * Api\Moves@store- creating the 'moves'. The answer is always the 'game' with 'squares' a message
    and 'last_move', to satisfy the tasks requirements, although its not necessary to have this
    attribute as all 'moves' could be included in the 'game' and frontend could pick up the last one.
    
- [x] Missing Features

    * Computer never looses. Its probably possible to devise a strategy and code it with predicates 
    using 'lines' and 'squares' of the 'game'
    
    * User support. With login, passwords etc. Where you have your own space, not just one year 
    of session.
    
    * Replay. All the 'moves' are saved with the order. The replay would involve playing them again
    on empty board. Javascript calls to the server every 'period'.
    
    * Undo. Just update the squares of the last couple moves. This undo mechanic could also be 
    merged/implemented as another type of a 'move'.

    
- [x] Unit Tests

    * Simply creating arbitrary size game, checking if it has all the squares with the right status.
    
    * Simulating a game 'between two computers', and make sure it resolves to expected state.
