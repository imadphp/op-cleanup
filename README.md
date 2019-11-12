# 1Password clean-up tool

## What is it?
This is a very small script that interacts with the [1password command
line tool](https://app-updates.agilebits.com/product_history/CLI) to try and find old
website login entries. It uses [guzzle](https://github.com/guzzle/guzzle) to perform http
requests to websites listed in your entries. It will list the urls that either fail
to respond within 20 seconds or have guzzle throw any other exception.
The script has no access to the passwords, neither encrypted or plain.

The absence of a framework is intentional.

## Requirements
- PHP
- Composer
- 1password cli

## How to use

### 1. Install 1password cli tool
This is documented [here](https://support.1password.com/command-line-getting-started/).

### 2. Run the script

Clone the repository, download the dependencies, sign-in to 1password and run it, as follows.

```bash
# Clone this repository
mkdir -p ~/op-cleanup && cd "$_" # Or anywhere, obviously
git clone https://github.com/imadphp/op-cleanup.git .

# Install composer dependencies
composer install

# Sign in to 1password through cli
eval $(op signin)

# Run the script
php ./op-cleanup
```

## Missing features
1. Allow deleting obsolete entries after confirmation
2. Differentiate between actual broken links and temporary http errors
3. Find duplicate website entries
4. Find urls with possibly useless query parameters like `?next=` or `?utm_source` and trim them.
5. Probably pick a framework before working on any of the above
