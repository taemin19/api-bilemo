Feature: Product
  In order to use the API
  As an API client
  I need to be able to retrieve one product / a collection of products

  Scenario: Retrieve one product
    When I send a "GET" request to "/products/1"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node "id" should contain "1"
    And the JSON node "model" should exist
    And the JSON node "brand" should exist
    And the JSON node "description" should exist
    And the JSON node "offers" should exist
    And the JSON node "offers[0].id" should exist
    And the JSON node "offers[0].price" should exist
    But the JSON node "offers[0].storage" should not exist
    But the JSON node "offers[0].product" should not exist

  Scenario: Retrieve a collection of products
    When I send a "GET" request to "/products"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node " " should have 10 elements
    And the JSON node "[0].id" should exist
    And the JSON node "[0].model" should exist
    But the JSON node "[0].brand" should not exist
    But the JSON node "[0].description" should not exist
    But the JSON node "[0].offers" should not exist

  Scenario: Proper 404 exception on no product
    When I send a "GET" request to "/products/0"
    Then the response status code should be 404
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node "message" should contain "Not found."
