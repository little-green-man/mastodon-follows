# Mastodon Follows

This tool was created by [Little Green Man](https://lgm.ltd) to help you discover information about Mastodon accounts followed by an account you specify, helping you to find good people to follow.

## Use

To use this tool, clone this repository to your local computer and run `mastodon-follows`.

Required arguments:

-   url: The URL of the following page to gather data from

Optional arguments:

-   personalInstance: Provide this to be provided with links that allow you to follow users more easily
-   maxPages: Provide this as a positive integer to stop processing after so many pages of users

```
mastodon-follows https://social.lgm.ltd/@elliot/following --personalInstance=social.lgm.ltd
```

A file called "follows.html" will be produced in your current directory.

## Development

The tool is developed by [Little Green Man](https://lgm.ltd), open source, and PRs are welcome.

## License

This software is licensed under the MIT license.
