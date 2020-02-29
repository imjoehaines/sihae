Feature: Create a New Blog Post
  As a Sihae user
  I want to be able to write a blog post
  So that I can share my thoughts

  @database @loginAdmin
  Scenario: Creating a new post
    Given I am on "/post/admin/new"
    When I fill in "title" with "my post"
    And I fill in "body" with "some good text that i wrote"
    And I press "submit"
    Then I should be on "/post/my-post"
    And I should see "my post"
    And I should see "some good text that i wrote"

  @database @loginAdmin
  Scenario: Creating a new post but failing validation
    Given I am on "/post/admin/new"
    When I fill in "title" with "a"
    And I fill in "body" with "b"
    And I press "submit"
    Then I should be on "/post/admin/new"
    And I should see "Oops! Please fix the following errors"
    And I should see "Title: not at least 3 characters"
    And I should see "Body: not at least 10 characters"

  @database @loginAdmin
  Scenario: Creating a post with a duplicate title
    Given there is a post:
      | title       | body                                                                      |
      | A Cool Post | A bunch of cool text about my awesome blog post that I totally just wrote |
    And I am on "/post/admin/new"
    When I fill in "title" with "A Cool Post"
    And I fill in "body" with "some good text that i wrote"
    And I press "submit"
    Then the url should match "post/a-cool-post-[0-9]{8}"
    And I should see "A Cool Post"
    And I should see "some good text that i wrote"
    Given I am on "/"
    Then I should see "A Cool Post" in the ".post-list > li:nth-child(1)" element
    And I should see "A Cool Post" in the ".post-list > li:nth-child(2)" element
