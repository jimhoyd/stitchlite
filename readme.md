pagckages that I used
* laravel 5.2
* Guzzle 6

problems i ran into 
* ran into issues with laravel migration and foreign keys
* latest guzzle now implemented psr7, and dropper response->json(), has to hunt for a solution
* did not completely implement the oauth2 request token workflow for vend
* 2 stage sync as in sync products then variants has a issue where because I cannot get an sku from shopify so I am using the handle as an interm solution
* made an assumption that syncing was always product -> variant
* another issue with not being familar with the framework, I created a seperate variants table
* refactor the vend class to avoid using normalize data and do it in one loop


things i like about laravel
* compared to zf2 it is a lot more structured, give more guidance on models creation 
* uses alot of the newer php functionality like traits and reflection
* has an interesting way to do DI, via typing the function
* is strongly tied to the equloent ORM
* built in Users concept out of the box
* middleware concept is nice, easier to undertand the request lifecycle
* out of the box trottling middleware
* psr4 is much nicer


0.) demo that it works
0.5) running on local
1.) talk about the database, migration and walk thru all the tables
2.) show the models
3.) show the controller
4.) show the sync abstraction and interface
5.) things that can be better


have not figured out a good way to handle shopify not having sku for parent product
move the price and quantity to the pivot table
optimize the sync for vend, to accomplish it in one loop
refactor the sync url to get from config
finish the oauth workflow with token refresh



