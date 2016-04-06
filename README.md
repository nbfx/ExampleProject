# ExampleProject

This project is a couple of my code examples. Hope you'll enjoy it!

## Structure

There are several types of classes I wrote (or even participated in writing):</br>
[Entity](src/ChangedDirName/Bundle/MainBundle/Entity/Image.php), [Service](src/ChangedDirName/Bundle/MainBundle/Service), [Functional](src/ChangedDirName/Bundle/MainBundle/Tests/Functional/Admin) and [Unit](src/ChangedDirName/Bundle/MainBundle/Tests/Unit/Service/Naming) tests.</br>
Lets see the tree of directories. It implements the Symfony project root dir structure, using the 'src' dir as example.</br>
```
.
├─ README.md
└─ src                                                      # The project's PHP code root dir
   └─ ChangedDirName                                        # The project name
      └─ Bundle                                             # Directory for bundles containing
         └─ MainBundle                                      # Main bundle
            ├─ Entity                                       # Directory with entities
            │  └─ Image.php                                 # Image entity
            ├─ Service                                      # Directory with services
            │  ├─ LocalePathGenerateService.php             # Generating the page path considering the locale
            │  └─ Naming                                    # The set of Image name and path generators
            │     ├─ ImageNamingService.php
            │     ├─ PageImageDirectoryNamingService.php
            │     └─ PageImageFileNamingService.php
            └─ Tests                                        # Directory with tests
               ├─ Functional                                # Functional tests
               │  ├─ Admin                                  # The Admin panel functionality test
               │  │  └─ AdminDefaultControllerTest.php
               │  └─ Api                                    # Api cluster configuration file insert test
               │     └─ ClusterApiPostTest.php
               │   
               └─ Unit                                      # Unit tests
                  └─ Service                                # Directory with services tests
                     └─ Naming                              # Tests for Image name and path generators
                        ├─ ImageNamingServiceTest.php
                        ├─ PageImageDirectoryNamingServiceTest.php
                        └─ PageImageFileNamingServiceTest.php
```
## License

These examples were published for informational purposes only.</br>
It won't work, even if you'll try to copy and run them, due the lack of dependencies.</br>
Please, let me know if you have any questions.
