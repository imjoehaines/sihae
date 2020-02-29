Feature: Authentication
  As a Sihae user
  I want to be the only one who can post to my blog
  So that I have control over my content

  Scenario: Not logged in
    Given I am on the homepage
    Then I should not see "Add a new post"

  Scenario: Trying to login with non-existent username
    Given I am on "/login"
    And I fill in "username" with "hello"
    When I press "submit"
    Then I should see "Oops! Please fix the following errors No user was found with these credentials, please try again"

  Scenario: Trying to login with incorrect details
    Given I am on "/login"
    And I fill in "username" with "testing"
    And I fill in "password" with "toasting???"
    When I press "submit"
    Then I should see "Oops! Please fix the following errors No user was found with these credentials, please try again"

  @database
  Scenario: Logged in as non-admin
    Given I am logged in
    And I am on the homepage
    Then I should not see "Add a new post"

  Scenario: Attempting to add a post when not logged in
    Given I am on "/post/admin/new"
    Then I should not see "Add a new post"
    But I should see "Oops!"
    And I should see "I couldn't find the page you requested, maybe you could try this one instead."

  @database @login
  Scenario: Attempting to add a post when logged in as a non-admin
    Given I am on "/post/admin/new"
    Then I should not see "Add a new post"
    But I should see "Oops!"
    And I should see "I couldn't find the page you requested, maybe you could try this one instead."

  @database @login
  Scenario: Hide edit and delete links from non-admins
    Given there is a post:
      | title                | body                |
      | Penny's Perfect Post | Penny's post's text |
    And I am on "/"
    Then I should see "Penny's Perfect Post"
    But I should not see "edit"
    And I should not see "delete"

  @database @loginAdmin
  Scenario: Logging out
    Given I am on "/post/admin/new"
    Then I should see "Add a new post"
    When I am on "logout"
    And I am on "/post/admin/new"
    Then I should not see "Add a new post"
