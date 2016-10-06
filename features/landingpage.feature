Feature: Landing Page
  As a Sihae user
  I want to have a landing page
  So that visitors know what my blog is about

  Scenario: Visiting a freshly installed Sihae blog
    Given I am on the homepage
    Then I should see "Sihae"
