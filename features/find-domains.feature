Feature: Test that the Find Domains subcommand works correctly.

  Background:
    Given a WP installation with the WPCOM Legacy Redirector plugin

  Scenario: WPCOM Legacy Redirector finds zero domains by default
    When I run `wp wpcom-legacy-redirector find-domains`
    Then STDOUT should contain:
      """
      Found 0 unique outbound domains.
      """

  Scenario: WPCOM Legacy Redirector does not allow redirect to disallowed host.
    When I try `wp wpcom-legacy-redirector insert-redirect /foo https://google.com`
    Then STDERR should contain:
      """
      Error: Couldn't insert /foo -> https://google.com (If you are doing an external redirect, make sure you safelist the domain using the "allowed_redirect_hosts" filter.)
      """

  Scenario: WPCOM Legacy Redirector finds one domain from one redirect
    Given I add google.com to allowed_redirect_hosts

    When I run `wp wpcom-legacy-redirector insert-redirect /foo https://google.com`
    And I run `wp wpcom-legacy-redirector find-domains`
    Then STDOUT should contain:
      """
      Found 1 unique outbound domain.
      google.com
      """

  Scenario: WPCOM Legacy Redirector finds one domain from multiple redirects
    Given I add google.com to allowed_redirect_hosts

    When I run `wp wpcom-legacy-redirector insert-redirect /foo https://google.com`
    And I run `wp wpcom-legacy-redirector insert-redirect /foo1 https://google.com/1`

    When I run `wp wpcom-legacy-redirector find-domains`
    Then STDOUT should contain:
      """
      Found 1 unique outbound domain.
      google.com
      """

  Scenario: WPCOM Legacy Redirector finds multiple domain from multiple redirects
    Given I add google.com to allowed_redirect_hosts
    And I add google.co.uk to allowed_redirect_hosts

    When I run `wp wpcom-legacy-redirector insert-redirect /foo https://google.com`
    And I run `wp wpcom-legacy-redirector insert-redirect /foo1 https://google.co.uk`

    When I run `wp wpcom-legacy-redirector find-domains`
    Then STDOUT should contain:
      """
      Found 2 unique outbound domains.
      google.com
      google.co.uk
      """
