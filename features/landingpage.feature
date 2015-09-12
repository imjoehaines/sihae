Feature: Landing Page
  As a Sihae user
  I want to have a landing page
  So that visitors know what my blog is about

  Scenario: Visiting a freshly installed Sihae blog
    Given I am on the homepage
    Then I should see "Sihae"

  @database
  Scenario: Visiting a Sihae blog with a title configured
    Given my blog is called "Bill's Blog"
    And I am on the homepage
    Then I should see "Bill's Blog"
