Feature: Create a New Blog Post
  As a Sihae user
  I want to be able to write a blog post
  So that I can share my thoughts

  @database
  Scenario: Creating a new post
    Given I am on "post/new"
    When I fill in "title" with "my post"
    And I fill in "body" with "some good text that i wrote"
    And I press "submit"
    Then I should be on "post/my-post"
    And I should see "my post"
    And I should see "some good text that i wrote"
