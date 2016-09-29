Feature: Settings
  As a Sihae user
  I want to be able to configute my blog
  So that it is personal to me

  @database @login
  Scenario: Configuring the title of a Sihae blog
    Given I am on "/settings"
    And I rename my blog to "Bill's Blog"
    And I am on "/"
    Then I should see "Bill's Blog"

  @database @login
  Scenario: Configuring the summary of a Sihae blog
    Given I am on "/settings"
    And I summarise my blog as "Bill's bloody brilliant blog"
    And I am on "/"
    Then I should see "Bill's bloody brilliant blog"

  @database @login
  Scenario: Configuring whether to show the login link
    Given I am on "/settings"
    And I turn "off" the login link
    And I am on "/"
    Then I should not see "Logout"
    But I am on "/settings"
    And I turn "on" the login link
    Then I should see "Logout"
