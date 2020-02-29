Feature: Blog Post
  As a Sihae user
  I want to be able to read a single blog post
  So that I can share my thoughts

  @database @createUser
  Scenario: Reading a Sihae blog post
    Given there is a post:
      | title       | body                                                                      |
      | A Cool Post | A bunch of cool text about my awesome blog post that I totally just wrote |
    And I am on "/post/a-cool-post"
    Then I should see "A Cool Post"
    And I should see "A bunch of cool text about my awesome blog post that I totally just wrote"
    And I should not see "A cool and good post"

  @database @createUser
  Scenario: 404 when accessing an invalid post
    Given there is a post:
      | title       | body                                                                      |
      | A Cool Post | A bunch of cool text about my awesome blog post that I totally just wrote |
    And I am on "/post/a-cool-post"
    Then I should not see "Oops!"
    And the response status code should be 200
    When I am on "/post/a-bad-post"
    Then I should see "Oops!"
    And the response status code should be 404

  @database @loginAdmin
  Scenario: 404 when editing an invalid post
    Given there is a post:
      | title       | body                                                                      |
      | A Cool Post | A bunch of cool text about my awesome blog post that I totally just wrote |
    And I am on "/post/admin/edit/a-cool-post"
    Then I should not see "Oops!"
    And the response status code should be 200
    When I am on "/post/admin/edit/a-bad-post"
    Then I should see "Oops!"
    And the response status code should be 404

  @database @loginAdmin
  Scenario: 404 when editing an invalid post
    Given there is a post:
      | title       | body                                                                      |
      | A Cool Post | A bunch of cool text about my awesome blog post that I totally just wrote |
    And I am on "/post/admin/delete/a-cool-post"
    Then I should not see "Oops!"
    And the response status code should be 200
    When I am on "/post/admin/delete/a-bad-post"
    Then I should see "Oops!"
    And the response status code should be 404
