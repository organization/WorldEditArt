WorldEditArt - ![WorldEditArt](plugin_icon.png)
===

Please see [the webpage on gh-pages](//pemapmodder.github.io/WorldEditArt/guide/) for user guides.

Check out the [Doxygen-generated API docs](//pemapmodder.github.io/WorldEditArt/doxygen).

TODO list:
- [ ] Gamma
    - [X] Doxygen setup
    - [ ] Frameworks
        - [X] Language
        - [ ] World editing session control system
            - [ ] Allow any command senders (via the CommandControlledSession class) to execute world editing
            - [X] Selections (support multiple selections at the same time)
        - [X] Spaces library
        - [X] Action (Redo/Undo) system and action pool (rheostats)
        - [ ] Providers framework
            - [X] Framework
            - [ ] Implementations
                - [ ] Filesystem
                - [ ] MySQL
        - [X] Base command class
    - [ ] Utils
        - [ ] Async database querying system
        - [X] [Non-threaded integer-object pool](src/pemapmodder/worldeditart/utils/OrderedObjectPool.php)
    - [ ] Features
        - [ ] 
        - [ ] Safety
            - [ ] Sudo mode
            - [ ] Safe mode
                - [ ] Marking and storing of UCZs
                - [ ] Safe mode
        - [ ] Commands
            - [ ] Selection creation
                - [ ] //shoot
                - [ ] //grow
                - [ ] //cyl
                - [ ] //sph
                - [ ] //desel
                - [ ] //1, //2
            - [ ] Selection processing
                - [ ] //set
                - [ ] //replace
                - [ ] //test
            - [ ] Copying
                - [ ] //copy
                - [ ] //cut
                - [ ] //paste
        - [ ] Jump
        - [ ] Wand
        - [ ] Macros
            - [ ] Storage
            - [ ] Database

Compiling
===
To compile this plugin, simply run [`php make.php`](make.php), and a new phar build will be created in the [`bin`](bin/) directory. **Note that bin/id.json and bin/WorldEditArt_Dev.phar, which are under the Git tree, will be modified.**
