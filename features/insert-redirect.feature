Feature: Test that the Insert Redirect subcommand works correctly.

  Background:
    Given a WP installation with the WPCOM Legacy Redirector plugin

  Scenario: WPCOM Legacy Redirector nserts a redirect with a path
    Given I run `wp post create --post_title='Bar' --post_name='bar' --post_status="publish"`

    When I run `wp wpcom-legacy-redirector insert-redirect /foo /bar`
    Then STDOUT should contain:
      """
      Success: Inserted /foo -> /bar
      """

  @broken
  Scenario: WP-CLI inserts a redirect with a post ID
    Given I run `wp post create --post_title='Test post' --post_status="publish" --porcelain`
    Then save STDOUT as {POST_ID}
    Given I run `wp post list`
    Then STDOUT should contain:
      """
      Test
      """

    When I run `wp wpcom-legacy-redirector insert-redirect /foo1 {POST_ID}`
    Then STDOUT should contain:
      """
      Success: Inserted /foo1 -> {POST_ID}
      """
