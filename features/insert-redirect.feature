Feature: Test that the Insert Redirect subcommand works correctly.

  Background:
    Given a WP installation with the WPCOM Legacy Redirector plugin

  Scenario: WPCOM Legacy Redirector inserts a redirect with a path
    Given there is a published post with a slug of bar

    When I run `wp wpcom-legacy-redirector insert-redirect /foo /bar`
    Then STDOUT should contain:
      """
      Success: Inserted /foo -> /bar
      """

  Scenario: WPCOM Legacy Redirector inserts a redirect to a URL
    # example.com seems to have an automatic bypass on allowed_redirect_hosts filter unlike other hosts.
    When I run `wp wpcom-legacy-redirector insert-redirect /foo https://example.com`
    Then STDOUT should contain:
      """
      Success: Inserted /foo -> https://example.com
      """

  Scenario: WPCOM Legacy Redirector can't insert a redirect to itself
    When I try `wp wpcom-legacy-redirector insert-redirect /foo /foo`
    Then STDERR should contain:
      """
      Error: Couldn't insert /foo -> /foo ("Redirect From" and "Redirect To" values are required and should not match.)
      """

  @broken
  # Maybe Behat can't handle the wp_remote_get() check?
  Scenario: WPCOM Legacy Redirector can't insert a redirect from a published page
    Given there is a published post with a slug of bar

    When I try `wp wpcom-legacy-redirector insert-redirect /bar /hello-world`
    Then STDERR should contain:
      """
      Error: Couldn't insert /bar -> /hello-world (Redirects need to be from URLs that have a 404 status.)
      """

  @broken
  # See https://github.com/Automattic/WPCOM-Legacy-Redirector/issues/117.
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
