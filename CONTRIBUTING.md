> Thank you for considering contributing to this project! We appreciate your time and effort in improving our codebase.

# Contributing Guide

This project adheres to [The Code Manifesto](http://codemanifesto.com) as its guidelines for contributor interactions.

## The Code Manifesto

We want to work in an ecosystem that empowers developers to reach their potential--one that encourages growth and
effective collaboration. A space that is safe for all.

A space such as this benefits everyone that participates in it. It encourages new developers to enter our field. It is
through discussion and collaboration that we grow, and through growth that we improve.

In the effort to create such a place, we hold to these values:

1. **Discrimination limits us.** This includes discrimination on the basis of race, gender, sexual orientation, gender
   identity, age, nationality, technology and any other arbitrary exclusion of a group of people.
2. **Boundaries honor us.** Your comfort levels are not everyone’s comfort levels. Remember that, and if brought to your
   attention, heed it.
3. **We are our biggest assets.** None of us were born masters of our trade. Each of us has been helped along the way.
   Return that favor, when and where you can.
4. **We are resources for the future.** As an extension of #3, share what you know. Make yourself a resource to help
   those that come after you.
5. **Respect defines us.** Treat others as you wish to be treated. Make your discussions, criticisms and debates from a
   position of respectfulness. Ask yourself, is it true? Is it necessary? Is it constructive? Anything less is
   unacceptable.
6. **Reactions require grace.** Angry responses are valid, but abusive language and vindictive actions are toxic. When
   something happens that offends you, handle it assertively, but be respectful. Escalate reasonably, and try to allow
   the offender an opportunity to explain themselves, and possibly correct the issue.
7. **Opinions are just that: opinions.** Each and every one of us, due to our background and upbringing, have varying
   opinions. That is perfectly acceptable. Remember this: if you respect your own opinions, you should respect the
   opinions of others.
8. **To err is human.** You might not intend it, but mistakes do happen and contribute to build experience. Tolerate
   honest mistakes, and don't hesitate to apologize if you make one yourself.

## How to contribute

This is a collaborative effort. We welcome all contributions submitted as pull requests.

(Contributions to wording and style are also welcome.)

### Bugs

A bug is a demonstrable problem that is caused by the code in the repository. Good bug reports are extremely helpful –
thank you!

Please try to be as detailed as possible in your report. Include specific information about the environment – version of
PHP, etc. and steps required to reproduce the issue.

### Pull Requests

Good pull requests – patches, improvements, new features – are a fantastic help. Before create a pull request, please
follow these instructions:

* The code must follow
  the [PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).
  Run `composer cs-fix` to fix your code before commit.
* Write tests
* Document any change in `README.md` and `CHANGELOG.md`
* One pull request per feature. If you want to do more than one thing, send multiple pull request

### Running tests

```sh
composer test
```

---

Here are some guidelines to help you get started:

## Getting Started

1. **Fork the repository**: Click the "Fork" button at the top right of this page to create a copy of this repository in
   your GitHub account.
2. **Clone your fork**: Clone your forked repository to your local machine using:

```sh
git clone https://github.com/your-username/your-fork.git
```

3. **Create a branch**: Create a new branch for your changes:

```sh
git checkout -b your-branch-name
```

## Making Changes

1. **Code changes**: Make your changes to the codebase. Ensure that your code follows the project's coding standards and
   passes all tests.
2. **Update documentation**: If your changes affect documentation, please update it accordingly.
3. **Update version in `composer.json`**: Whenever you make any updates to the code, please remember to update the
   version in the `composer.json` file under the `scripts` section. For example:

```json
{
  "scripts": {
    "package:version": "echo v1.10.3"
  }
}
```

Increment the version number appropriately following [Semantic Versioning](https://semver.org/spec/v2.0.0.html)
principles (e.g., **v1.10.2** to **v1.10.3**).

## Submitting Your Changes

1. **Commit your changes**: Commit your changes with a clear and descriptive message:

```sh
git commit -m "Description of your changes"
```

2. **Push to your fork**: Push your changes to your forked repository:

```sh
git push origin your-branch-name
```

3. **Create a pull request**: Open a pull request from your forked repository to the main repository. Provide a clear
   description of your changes and any relevant information.

## Code Review

- Your pull request will be reviewed by project maintainers. Please be responsive to any feedback or requested changes.
- Once your changes are approved, they will be merged into the main branch.

## Thank You!

We appreciate your contribution to the project. If you have any questions or need further assistance, please don't
hesitate to reach out.
