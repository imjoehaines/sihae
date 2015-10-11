Feature: Edit a Blog Post
  As a Sihae user
  I want to be able to edit a blog post
  So that I can fix spelling errors and update posts

  @database @login
  Scenario: Editing a post
    Given there is a post:
      | title                | summary     | body                |
      | Penny's Perfect Post | A cool post | Penny's post's text |
    And I am on "post/edit/penny-s-perfect-post"
    When I fill in "title" with "Penny's Perfecter Post"
    And I fill in "body" with "Different post text"
    And I press "submit"
    Then I should be on "post/penny-s-perfect-post"
    And I should see "Successfully edited your post!"
    And I should see "Penny's Perfecter Post"
    And I should see "Different post text"