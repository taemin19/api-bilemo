Feature: Authentication
  In order to access protected resource
  As an API client
  I need to be able to authenticate

  Scenario: Authenticate the client to obtain the token
    Given the following clients exist:
      | name | username | password |
      | john | doe      | johndoe  |
    When I send a "POST" request to "/login_check" with body:
    """
    {
      "username": "doe",
      "password": "johndoe"
    }
    """
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node "token" should exist

  Scenario: Proper 400 exception if invalid body on client login
    When I send a "POST" request to "/login_check" with body:
    """
    """
    Then the response status code should be 400
    And the header "Content-Type" should be equal to "application/problem+json"
    And the JSON node "status" should contain "400"
    And the JSON node "type" should contain "about:blank"
    And the JSON node "title" should contain "Bad Request"
    And the JSON node "detail" should contain "Invalid JSON."

  Scenario: Proper 401 exception when the client authentication failed to obtain the token
    Given the following clients exist:
      | name | username | password |
      | john | doe      | johndoe  |
    When I send a "POST" request to "/login_check" with body:
    """
    {
      "username": "doe",
      "password": "john"
    }
    """
    Then the response status code should be 401
    And the header "Content-Type" should be equal to "application/problem+json"
    And the JSON node "status" should contain "401"
    And the JSON node "type" should contain "about:blank"
    And the JSON node "title" should contain "Unauthorized"
    And the JSON node "detail" should contain "Bad credentials, please verify that your username/password are correctly set"

  Scenario Outline: Proper 401 exception when accessing resources without authentication
    When I send a "<method>" request to "<url>"
    Then the response status code should be 401
    And the header "Content-Type" should be equal to "application/problem+json"
    And the header "WWW-Authenticate" should be equal to "Bearer"
    And the JSON node "status" should contain "401"
    And the JSON node "type" should contain "about:blank"
    And the JSON node "title" should contain "Unauthorized"
    And the JSON node "detail" should contain "Authentication required."

    Examples:
      | url         | method |
      | /products   | GET    |
      | /products/1 | GET    |
      | /users      | GET    |
      | /users/1    | GET    |
      | /users      | POST   |
      | /users/1    | DELETE |
