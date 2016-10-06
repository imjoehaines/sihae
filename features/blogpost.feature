Feature: Blog Post
  As a Sihae user
  I want to be able to read a single blog post
  So that I can share my thoughts

  @database
  Scenario: Reading a Sihae blog post
    Given there is a post:
      | title       | body                                                                      |
      | A Cool Post | A bunch of cool text about my awesome blog post that I totally just wrote |
    And I am on "post/a-cool-post"
    Then I should see "A Cool Post"
    And I should see "A bunch of cool text about my awesome blog post that I totally just wrote"
    And I should not see "A cool and good post"
