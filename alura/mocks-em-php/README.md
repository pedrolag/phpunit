## Hey there! 🤙

I've created this repository to better understand and practice **PHPUnit** ✨

🟡 **THIS IS A PRACTICE PROJECT**

🟡 **THE CODE IN THIS PROJECT <ins>WAS NOT</ins> ENTIRELY CREATED BY ME**

🟡 **THE CODE AND COMMENTS ARE IN PT-BR**

#

### 🔎 About this project

In this repository you will find some services, wich one of them is tested using PHPUnit. More specifically, this project is focused in unit testing with **mocks**.

Some of the questions I had before this exercise where:

- What exactly is a "mock"?
	- A "mock" is basically an immitation of a class.
- What is the purpose of creating mocks?
	- Mocks are important for unit testing. With mocks as your dependences, you can focus in testing only what you really want to test.
- Is it hard to learn?
	- Not really. The only "hard" part is learning how to structure and use the methods that are available through the testing tool.
- Is it worth it?
	- If you are going to work with PHP, absolutely!

#### 🤔 How to setup this thing?

Open your terminal on the root of the project and use the following command:

```
composer install
```

#### 🤔 Yeah, but how do I run the project?

Open your terminal on the root of the project and use the following command:

```
vendor/bin/phpunit
```

After this, the test results should appear in your terminal. Something like this:

```
$ vendor/bin/phpunit
PHPUnit 8.1.5 by Sebastian Bergmann and contributors.

...............Erro ao enviar e-mail
Erro ao enviar e-mail
..                                                 17 / 17 (100%)

Time: 110 ms, Memory: 4.00 MB

OK (17 tests, 35 assertions)
```

And that's it!