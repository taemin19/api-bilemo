Feature: Offer
  In order to use the API
  As an API client
  I need to be able to retrieve one offer of a product / a collection of product offers

  Scenario: Retrieve one offer of a product
    When I send a "GET" request to "/products/1/offers/1"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node "id" should contain "1"
    And the JSON node "storage" should exist
    And the JSON node "price" should exist
    And the JSON node "product" should exist
    And the JSON node "product.id" should contain "1"
    And the JSON node "product.model" should exist
    But the JSON node "product.brand" should not exist
    But the JSON node "product.description" should not exist
    But the JSON node "product.offers" should not exist

  Scenario: Retrieve a collection of product offers
    When I send a "GET" request to "/products/1/offers"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node " " should have 1 elements
    And the JSON node "[0].id" should exist
    And the JSON node "[0].storage" should exist
    And the JSON node "[0].price" should exist
    And the JSON node "[0].product" should exist
    And the JSON node "[0].product.id" should exist
    And the JSON node "[0].product.model" should exist
    But the JSON node "[0].product.brand" should not exist
    But the JSON node "[0].product.description" should not exist
    But the JSON node "[0].product.offers" should not exist

  Scenario: Proper 404 exception on no offer
    When I send a "GET" request to "/products/1/offers/0"
    Then the response status code should be 404
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node "message" should contain "Not found."

  Scenario: Proper 404 exception on no product
    When I send a "GET" request to "/products/0/offers/1"
    Then the response status code should be 404
    And the header "Content-Type" should be equal to "application/json"
    And the response should be in JSON
    And the JSON node "message" should contain "Not found."
