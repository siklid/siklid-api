# Community Reviews

Siklid is an open-source project driven by the community. If you don't feel ready to contribute to code, you can still
help by reviewing issues and pull requests.

## Why review?

Reviewing is a great way to get familiar with the codebase and the project. It's also a great way to get to know the
community and the people working on the project.

On the [issue tracker](https://github.com/piscibus/siklid-api/issues), you can
find [items that need to be reviewed](https://github.com/piscibus/siklid-api/pulls):

- **Bug reports**: Needs to be checked for completeness and reproducibility. You can help by following up with the
  reporter to add more information or add them to the issue if you can reproduce the bug.
- **Feature requests**: Needs to be checked for completeness and prioritized. You can help by adding more information
  to the issue or by adding your own use case.
- **Pull requests**: Needs to be reviewed for code quality and correctness. You can help by reviewing the code and
  leaving comments.

Anyone who has familiarity with PHP can review issues and pull requests. You don't need to be an expert to help.

## Be Constructive

When reviewing issues and pull requests, you should be constructive. You should always be respectful and polite.
Remember that you are looking at the result of someone else's hard work. A good review comment thanks the contributor
for their, identifies what is being reviewed, and explains why the current implementation is not satisfactory. It should
also suggest an alternative implementation.

## Testing Pull Requests

If you are reviewing a pull request, you should test it. You can test it by checking out the branch and
running `composer test` command in the root of the project inside the docker container:

To check out the branch and run the tests:

```bash
git fetch origin pull/ID/head:BRANCHNAME
git checkout BRANCHNAME
docker compose exec -i php composer test
```

For example, if you want to test pull request #1234, you would run:

```bash
git fetch origin pull/1234/head:pr-1234
git checkout pr-1234
docker compose exec -i php composer test
```

## Updating status

After you have reviewed an issue or pull request, you should update the status. You can do this by adding a comment.

Here is a sample comment for a PR that is not ready for merging:
> Thank you, @username, for working on this. Could you please address the comments I left on the code?

Here is a sample comment for a PR that is ready for merging:

> Thank you, @username, for working on this. I have tested the code, and it works as expected. :rocket:
