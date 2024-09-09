## Focal-X-Task-5

#Task Management API

## project details
Task management system
Through which users and managers are added, tasks are added and the task is assigned to the user required to implement it.
The system enables us to track the status of the task through the status of each task. We can also add a priority to the task to distribute priorities between tasks.

# Roles
    Admin - Manager - User  (Users with these roles are added through RolesSeeder.)

**Packages**
    - tymon/jwt-auth for  handle JWT tokens.
    - spatie/laravel-permission -  This package allows us to manage user permissions and roles in our application.Primary key in role and permission models is UUID.


**Response**
    - A middleware called JsonResponse was used to model all responses, whether in valid or exception cases.
    - Middleware is added by default. I make append this middleware in Karnel.

**Exception**
    - handle Exceptions in Exception/Handler.php is used to capture all types of Exceptions and model their display using a
      status code.

**Request**
    - All requests are processed within Form Request to verify their validity, organize them, and benefit from all its features as needed.
    - The Request form has been used with all the features and services available in it

**Models**
    - User : 
        Primary key in user model is UUID (So $incrementing = false).
        The model uses traits of HasRole and SoftDeletes.
        The model doesn't have timestamps.
        Supports soft delete
    - User_Task:
        The model has timestamps (create and update).
        The model connects to a table called user_tasks (protected $table = 'user_tasks';);
        Supports soft delete

 **Services**
    - Services were used when needed, i.e. when there was complexity in the operations, they were moved from the
      controller to the service.

 **APIS**
    - using JWT Token with auth:api middleware to verify your login.
    - Admin can do all application operations. Added within AuthServiceProvider
    - CRUD Users .( Only admin permissions)
    - CRUD tasks (admin  and user permissions)
    - edit status task (admin  and user permissions)
    - change password (User can change default password.)

 **Validations**
    - Use simple and important expressions and rules in the validation process like: required , exists , unique ,
      numeric ,.......
    - (note) : We did not focus on the(failedAuthorization, failedValidation) cases because they are all handled in the
      handle exception.

 
## Additional Notes
- Consider the code fragmentation mechanism
- Split task state change into multiple links to separate operations within APIs
- For each method there is a comment & document to explain the method operations and define params & returns.
- postman collection attached in laravel project

