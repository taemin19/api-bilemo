Feature: User
  In order to use the API
  As an API client
  I need to be able to retrieve one user / a collection of users

  Scenario: Retrieve one user
    When I send a "GET" request to "/users/1"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node "id" should contain "1"
    And the JSON node "firstname" should exist
    And the JSON node "lastname" should exist
    And the JSON node "email" should exist

  Scenario: Retrieve a collection of users
    When I send a "GET" request to "/users"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node " " should have 5 elements
    And the JSON node "[0].id" should exist
    And the JSON node "[0].firstname" should exist
    And the JSON node "[0].lastname" should exist
    But the JSON node "[0].email" should not exist

  Scenario: Proper 404 exception on no user
    When I send a "GET" request to "/users/0"
    Then the response status code should be 404
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node "message" should contain "Not found."
