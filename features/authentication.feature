Feature: Authentication
  As a Sihae user
  I want to be the only one who can post to my blog
  So that I have control over my content

  Scenario: Not logged in
    Given I am on the homepage
    Then I should not see "Add a new post"

  Scenario: Logged in
    Given I am logged in
    And I am on the homepage
    Then I should see "Add a new post"

  Scenario: Attempting to add a post when not logged in
    Given I am on "new"
    Then I should not see "Add a new post"
    But I should see "Oops!"
    And I should see "I couldn't find the page you requested, maybe you could try this one instead."
