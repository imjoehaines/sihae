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
    Given I am on "/post/new"
    Then I should not see "Add a new post"
    But I should see "Login"
    And I should see an "#email" element
    And I should see a "#password" element
