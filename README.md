# Certistore
This is the main API backend for the project. 

## How to implement
1. Download / Clone the file.
2. In root folder go to **.env** file. Then change the following code.
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3308
    DB_DATABASE=certistore
    DB_USERNAME=root *User name created*
    DB_PASSWORD=     *Password created*
   ```
3. In the root folder open commandline & give following commands.
    - To migrate databases
        ```
        php artisan migrate
        ```
    - Install passport keys
        ```
        php artisan passport:install
        ```
    - Run the local server
        ```
        php artisan serve
        ```
        
## API documentation
  - Only JSON are accepted
  - Responses are also JSON 
  
    ### User bound actions
    
    #### List all the registered users
        - API path 127.0.0.1:8000/api/v1/user
        - API method : GET 
        - Paginated for 20 per time  
        
    #### Store user 
         - API path 127.0.0.1:8000/api/v1/user
         - API method : POST
        
        - Parameters include with validator
            ```
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'user_type' => 'required',
            'password' => 'required',
            ```
        - Returned with,
            - User INFO
            - User Auth token
            
    #### Show a unique user details
         - API path 127.0.0.1:8000/api/v1/user/{userID}
         - API method : GET 
    
    #### Update a unique user details 
         - API path 127.0.0.1:8000/api/v1/user/{userId}
         - API method : POST
         
         - Parameters include with validator
            ```
            _method = PUT {OR} PATCH
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'user_type' => 'required',
            ```
    #### Destroy a user 
         - API path 127.0.0.1:8000/api/v1/user/{userId}
         - API method : DELETE
         
         - User record will be deleted.
         
