<?php

declare(strict_types=1);

namespace PackageVersions;

use OutOfBoundsException;

/**
 * This class is generated by ocramius/package-versions, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 */
final class Versions
{
    public const ROOT_PACKAGE_NAME = 'symfony/symfony';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    public const VERSIONS          = array (
  'doctrine/annotations' => '1.10.3@5db60a4969eba0e0c197a19c077780aadbc43c5d',
  'doctrine/cache' => '1.10.1@35a4a70cd94e09e2259dfae7488afc6b474ecbd3',
  'doctrine/collections' => '1.6.5@fc0206348e17e530d09463fef07ba8968406cd6d',
  'doctrine/common' => '2.13.3@f3812c026e557892c34ef37f6ab808a6b567da7f',
  'doctrine/dbal' => '2.10.2@aab745e7b6b2de3b47019da81e7225e14dcfdac8',
  'doctrine/doctrine-bundle' => '2.1.0@0fb513842c78b43770597ef3c487cdf79d944db3',
  'doctrine/doctrine-migrations-bundle' => '2.1.2@856437e8de96a70233e1f0cc2352fc8dd15a899d',
  'doctrine/event-manager' => '1.1.0@629572819973f13486371cb611386eb17851e85c',
  'doctrine/inflector' => '1.4.3@4650c8b30c753a76bf44fb2ed00117d6f367490c',
  'doctrine/instantiator' => '1.3.1@f350df0268e904597e3bd9c4685c53e0e333feea',
  'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042',
  'doctrine/migrations' => '2.2.1@a3987131febeb0e9acb3c47ab0df0af004588934',
  'doctrine/orm' => 'v2.7.3@d95e03ba660d50d785a9925f41927fef0ee553cf',
  'doctrine/persistence' => '1.3.7@0af483f91bada1c9ded6c2cfd26ab7d5ab2094e0',
  'doctrine/reflection' => '1.2.1@55e71912dfcd824b2fdd16f2d9afe15684cfce79',
  'doctrine/sql-formatter' => '1.1.0@5458bdcf176f6a53292e3f0cc73f292d6302fb0f',
  'egulias/email-validator' => '2.1.17@ade6887fd9bd74177769645ab5c474824f8a418a',
  'james-heinrich/getid3' => 'v1.9.19@c6fd499a690ae67eea2eec6b2edba5df13f60f6f',
  'laminas/laminas-code' => '3.4.1@1cb8f203389ab1482bf89c0e70a04849bacd7766',
  'laminas/laminas-eventmanager' => '3.2.1@ce4dc0bdf3b14b7f9815775af9dfee80a63b4748',
  'laminas/laminas-zendframework-bridge' => '1.0.4@fcd87520e4943d968557803919523772475e8ea3',
  'monolog/monolog' => '2.1.0@38914429aac460e8e4616c8cb486ecb40ec90bb1',
  'ocramius/package-versions' => '1.8.0@421679846270a5772534828013a93be709fb13df',
  'ocramius/proxy-manager' => '2.8.0@ac1dd414fd114cfc0da9930e0ab46063c2f5e62a',
  'phpdocumentor/reflection-common' => '2.1.0@6568f4687e5b41b054365f9ae03fcb1ed5f2069b',
  'phpdocumentor/reflection-docblock' => '5.1.0@cd72d394ca794d3466a3b2fc09d5a6c1dc86b47e',
  'phpdocumentor/type-resolver' => '1.1.0@7462d5f123dfc080dfdf26897032a6513644fc95',
  'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8',
  'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
  'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0',
  'psr/link' => '1.0.0@eea8e8662d5cd3ae4517c9b864493f59fca95562',
  'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc',
  'sensio/framework-extra-bundle' => 'v5.5.5@c76bb1c5c67840ecb6d9be8e9d8d7036e375e317',
  'symfony/asset' => 'v5.0.9@aaf4ba865c02f6df999166a0148d56f2b11b11fb',
  'symfony/cache' => 'v5.0.9@77b641e27716591ab8cdc1eed3793828056c5e3d',
  'symfony/cache-contracts' => 'v2.1.2@87c92f62c494626598e9148208aaa6d1716b8e3c',
  'symfony/config' => 'v5.0.9@6c2a080f8caa60daeeb5ccca21efc62c5d24c685',
  'symfony/console' => 'v5.0.9@f91588c06ab1a03cfd3b27c3335fae93ee90325d',
  'symfony/dependency-injection' => 'v5.0.9@f4159bdcaec10a2d648e19ca00a3ea6545b3ff5b',
  'symfony/doctrine-bridge' => 'v5.0.9@0cceaf05876c757a1b754614656dae503f7de488',
  'symfony/dotenv' => 'v5.0.9@efd887f012127acad22325d109fe8ddf635f1f97',
  'symfony/error-handler' => 'v5.0.9@d98fc4688edb67a482046ed396e94bf523072d2a',
  'symfony/event-dispatcher' => 'v5.0.9@9f2efcbb6f7bc86d7cb0ca55c5ea105a2a4ed105',
  'symfony/event-dispatcher-contracts' => 'v2.1.2@405952c4e90941a17e52ef7489a2bd94870bb290',
  'symfony/expression-language' => 'v5.0.9@31ea3085d94d2656a3560ba303e0e27456c5d265',
  'symfony/filesystem' => 'v5.0.9@6edf8b9e64e662fcde20ee3ee2ec46fdcc8c3214',
  'symfony/finder' => 'v5.0.9@127bccabf3c854625af9c0162779cf06bc1dd352',
  'symfony/flex' => 'v1.7.1@a53056880aae0ce034ac6c38906e162ee5cfd2df',
  'symfony/form' => 'v5.0.9@c54fefa05427d0668821e038d4435a03345b4684',
  'symfony/framework-bundle' => 'v5.0.9@ad83c652e49fc35ea14bd6317eb70b96e3a36ef0',
  'symfony/http-client' => 'v5.0.9@4593ee5769c827fcf655e853c93a36eb54b7d063',
  'symfony/http-client-contracts' => 'v2.1.2@f8bed25edc964d015bcd87f1fec5734963931910',
  'symfony/http-foundation' => 'v5.0.9@c9b0348b8b58abc024e080c0b12430e7def32076',
  'symfony/http-kernel' => 'v5.0.9@1d303b84848d155143c4e87e17675e42cc23ee88',
  'symfony/inflector' => 'v5.0.9@7eff2643934179cd0e5a6609a583fc22fc495fc4',
  'symfony/intl' => 'v5.0.9@351e2b7861ab9cae7436dbffe5401581540a6d4e',
  'symfony/mailer' => 'v5.0.9@99689159f32cfa71027cc92e47c0fbb373f0aba0',
  'symfony/mime' => 'v5.0.9@c7fb653965541595e89847fddf60860be42513ba',
  'symfony/monolog-bridge' => 'v5.0.9@75344ab5ca8b09794e8d189ffc91321ea25eafdc',
  'symfony/monolog-bundle' => 'v3.5.0@dd80460fcfe1fa2050a7103ad818e9d0686ce6fd',
  'symfony/notifier' => 'v5.0.9@e5d0bb4a54509f817814cb4f267c8426e65396e4',
  'symfony/options-resolver' => 'v5.0.9@48f41cbb1a2e52b76ca9c76a82f04a05b2d58e3c',
  'symfony/orm-pack' => 'v1.0.8@c9bcc08102061f406dc908192c0f33524a675666',
  'symfony/polyfill-intl-grapheme' => 'v1.17.0@e094b0770f7833fdf257e6ba4775be4e258230b2',
  'symfony/polyfill-intl-icu' => 'v1.17.0@4ef3923e4a86e1b6ef72d42be59dbf7d33a685e3',
  'symfony/polyfill-intl-idn' => 'v1.17.0@3bff59ea7047e925be6b7f2059d60af31bb46d6a',
  'symfony/polyfill-intl-normalizer' => 'v1.17.0@1357b1d168eb7f68ad6a134838e46b0b159444a9',
  'symfony/polyfill-mbstring' => 'v1.17.0@fa79b11539418b02fc5e1897267673ba2c19419c',
  'symfony/polyfill-php73' => 'v1.17.0@a760d8964ff79ab9bf057613a5808284ec852ccc',
  'symfony/polyfill-php80' => 'v1.17.0@5e30b2799bc1ad68f7feb62b60a73743589438dd',
  'symfony/process' => 'v5.0.9@971862ab55d8154c2a2cfca31e8594d731e65e46',
  'symfony/property-access' => 'v5.0.9@dd88af11349e864caef645d94dbed065d86d7225',
  'symfony/property-info' => 'v5.0.9@c0e4310a3098e69b8bfe62f13b4287504c51c4fe',
  'symfony/routing' => 'v5.0.9@f32f36ee08fd427313f3574546eeb258aa0a752a',
  'symfony/security-bundle' => 'v5.0.9@7a2d9e8bada073430c9a2e14b465450f49254601',
  'symfony/security-core' => 'v5.0.9@310931796ebad7e57d0e318170eaa59842c1a39a',
  'symfony/security-csrf' => 'v5.0.9@155a413dc29400e74d2c06f5581da795200386c1',
  'symfony/security-guard' => 'v5.0.9@4d920d91fa44be8ebfe1a101dadde48181d8a4fb',
  'symfony/security-http' => 'v5.0.9@e18913e3663dde1d4712c921211d12185c323c6e',
  'symfony/serializer' => 'v5.0.9@6417fa4e3185d3c0144ba1f67d695d215ebf74a2',
  'symfony/serializer-pack' => 'v1.0.3@9bbce72dcad0cca797b678d3bfb764cf923ab28a',
  'symfony/service-contracts' => 'v2.1.2@66a8f0957a3ca54e4f724e49028ab19d75a8918b',
  'symfony/stopwatch' => 'v5.0.9@fbc3084469450c6f6616f5436a00e180ea9ff118',
  'symfony/string' => 'v5.0.9@1f16181787d56464c834a3ca577f385500ec9213',
  'symfony/translation' => 'v5.0.9@d3262422559eef735b12ed4c7a2fbe8be3ba8898',
  'symfony/translation-contracts' => 'v2.1.2@e5ca07c8f817f865f618aa072c2fe8e0e637340e',
  'symfony/twig-bridge' => 'v5.0.9@144f5f91a32b79328a8949089f9063c4fb41245c',
  'symfony/twig-bundle' => 'v5.0.9@348863cd784b10ea7e1485dc3003c738c6cdf547',
  'symfony/twig-pack' => 'v1.0.0@8b278ea4b61fba7c051f172aacae6d87ef4be0e0',
  'symfony/validator' => 'v5.0.9@8bc33218f83e0027fd93d1ce7275c406f36f1248',
  'symfony/var-dumper' => 'v5.0.9@2c787a1d9cb0ad32be7212301d227466d55c7ba9',
  'symfony/var-exporter' => 'v5.0.9@271df5d0dc00d231bf189d0c40de594d4353502e',
  'symfony/web-link' => 'v5.0.9@1b2e3621074e65632f9690c4d0cb59da8e71b4fc',
  'symfony/webpack-encore-bundle' => 'v1.7.3@5c0f659eceae87271cce54bbdfb05ed8ec9007bd',
  'symfony/yaml' => 'v5.0.9@29b60e88ff11a45b708115004fdeacab1ee3dd5d',
  'twig/extra-bundle' => 'v3.0.3@6eaf1637abe6b68518e7e0949ebb84e55770d5c6',
  'twig/twig' => 'v3.0.3@3b88ccd180a6b61ebb517aea3b1a8906762a1dc2',
  'webimpress/safe-writer' => '2.0.1@d6e879960febb307c112538997316371f1e95b12',
  'webmozart/assert' => '1.8.0@ab2cb0b3b559010b75981b1bdce728da3ee90ad6',
  'nikic/php-parser' => 'v4.5.0@53c2753d756f5adb586dca79c2ec0e2654dd9463',
  'symfony/browser-kit' => 'v5.0.9@16141bce671d4ee12cf45927e3ce6cd2f343c442',
  'symfony/css-selector' => 'v5.0.9@79c224cdbfae58d54b257a8c684ad445042c90f2',
  'symfony/debug-bundle' => 'v5.0.9@4bae28a913fa32ec123a37b3178b7b7d3a4ac323',
  'symfony/debug-pack' => 'v1.0.8@7310a66f9f81c9f292ff9089f0b0062386cb83fb',
  'symfony/dom-crawler' => 'v5.0.9@9d86e7382e7fe96889440e29c1965e4e4f6f4aec',
  'symfony/maker-bundle' => 'v1.19.0@bea8c3c959e48a2c952cc7c4f4f32964be8b8874',
  'symfony/phpunit-bridge' => 'v5.1.0@7a05a59154053d62674def66a5c99896113632f2',
  'symfony/profiler-pack' => 'v1.0.4@99c4370632c2a59bb0444852f92140074ef02209',
  'symfony/test-pack' => 'v1.0.6@ff87e800a67d06c423389f77b8209bc9dc469def',
  'symfony/web-profiler-bundle' => 'v5.0.9@80367b65c32d6f77dddfe180ec12635c616408a4',
  'paragonie/random_compat' => '2.*@400a6a0fb72a4610f053823d2c030365f8755225',
  'symfony/polyfill-ctype' => '*@400a6a0fb72a4610f053823d2c030365f8755225',
  'symfony/polyfill-iconv' => '*@400a6a0fb72a4610f053823d2c030365f8755225',
  'symfony/polyfill-php72' => '*@400a6a0fb72a4610f053823d2c030365f8755225',
  'symfony/polyfill-php71' => '*@400a6a0fb72a4610f053823d2c030365f8755225',
  'symfony/polyfill-php70' => '*@400a6a0fb72a4610f053823d2c030365f8755225',
  'symfony/polyfill-php56' => '*@400a6a0fb72a4610f053823d2c030365f8755225',
  'symfony/symfony' => 'dev-master@400a6a0fb72a4610f053823d2c030365f8755225',
);

    private function __construct()
    {
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     */
    public static function getVersion(string $packageName) : string
    {
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }
}
