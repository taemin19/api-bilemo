Feature: Product
  In order to use the API
  As an API client
  I need to be able to retrieve one product / a collection of products

  @login
  Scenario: Retrieve one product
    Given the following products exist:
      | model     | brand   | description | storage | color | price  |
      | galaxy S9 | samsung | new model   | 64      | black | 849.99 |
    When I send a "GET" request to "/products/1"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/hal+json"
    And the response should be in JSON
    And the JSON node "id" should contain "1"
    And the JSON node "model" should contain "galaxy S9"
    And the JSON node "brand" should contain "samsung"
    And the JSON node "description" should contain "new model"
    And the JSON node "storage" should contain "64"
    And the JSON node "color" should contain "black"
    And the JSON node "price" should contain "849.99"
    And the JSON node "_links.self.href" should contain "/products/1"

  @login
  Scenario: Retrieve a collection of products
    Given the following products exist:
      | model     | brand   | description | storage | color  | price   |
      | galaxy S9 | samsung | new model   | 64      | black  | 849.99  |
      | iphone X  | apple   | new model   | 128     | silver | 1089.99 |
      | p 20      | huawei  | new model   | 32      | blue   | 649.99  |
    When I send a "GET" request to "/products"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/hal+json"
    And the response should be in JSON
    And the JSON node " " should have 3 elements
    And the JSON node "[0].id" should contain "1"
    And the JSON node "[0].model" should contain "galaxy S9"
    And the JSON node "[0].brand" should contain "samsung"
    And the JSON node "[0].description" should contain "new model"
    And the JSON node "[0].storage" should contain "64"
    And the JSON node "[0].color" should contain "black"
    And the JSON node "[0].price" should contain "849.99"
    And the JSON node "[0]._links.self.href" should contain "/products/1"
    And the JSON node "[1].id" should contain "2"
    And the JSON node "[1].model" should contain "iphone X"
    And the JSON node "[1].brand" should contain "apple"
    And the JSON node "[1].description" should contain "new model"
    And the JSON node "[1].storage" should contain "128"
    And the JSON node "[1].color" should contain "silver"
    And the JSON node "[1].price" should contain "1089.99"
    And the JSON node "[1]._links.self.href" should contain "/products/2"
    And the JSON node "[2].id" should contain "3"
    And the JSON node "[2].model" should contain "p 20"
    And the JSON node "[2].brand" should contain "huawei"
    And the JSON node "[2].description" should contain "new model"
    And the JSON node "[2].storage" should contain "32"
    And the JSON node "[2].color" should contain "blue"
    And the JSON node "[2].price" should contain "649.99"
    And the JSON node "[2]._links.self.href" should contain "/products/3"

  @login
  Scenario: Proper 404 exception if a product is not found
    When I send a "GET" request to "/products/0"
    Then the response status code should be 404
    And the header "Content-Type" should be equal to "application/problem+json"
    And the JSON node "status" should contain "404"
    And the JSON node "type" should contain "about:blank"
    And the JSON node "title" should contain "Not Found"
    And the JSON node "detail" should contain 'No product found with id "0"'
