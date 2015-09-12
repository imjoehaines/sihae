Feature: Testing
  In order to test Laravel works
  As a dev
  I want to check that Laravel is installed

  Scenario: Home Page
    Given I am on the homepage
    Then I should see "Laravel 5"
