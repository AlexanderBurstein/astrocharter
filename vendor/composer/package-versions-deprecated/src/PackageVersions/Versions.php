<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = '__root__';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'composer/package-versions-deprecated' => '1.11.99.4@b174585d1fe49ceed21928a945138948cb394600',
  'doctrine/annotations' => '1.13.2@5b668aef16090008790395c02c893b1ba13f7e08',
  'doctrine/cache' => '2.1.1@331b4d5dbaeab3827976273e9356b3b453c300ce',
  'doctrine/collections' => '1.6.8@1958a744696c6bb3bb0d28db2611dc11610e78af',
  'doctrine/common' => '3.2.0@6d970a11479275300b5144e9373ce5feacfa9b91',
  'doctrine/dbal' => '3.1.3@96b0053775a544b4a6ab47654dac0621be8b4cf8',
  'doctrine/deprecations' => 'v0.5.3@9504165960a1f83cc1480e2be1dd0a0478561314',
  'doctrine/doctrine-bundle' => '2.3.2@d6b3c37804539a24ba8a7d647a6144cab2f13242',
  'doctrine/doctrine-migrations-bundle' => '3.2.1@c1b10bc1466e08bba82640e49c7bbcce0c9853c2',
  'doctrine/event-manager' => '1.1.1@41370af6a30faa9dc0368c4a6814d596e81aba7f',
  'doctrine/inflector' => '2.0.4@8b7ff3e4b7de6b2c84da85637b59fd2880ecaa89',
  'doctrine/instantiator' => '1.4.0@d56bf6102915de5702778fe20f2de3b2fe570b5b',
  'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042',
  'doctrine/migrations' => '3.3.2@b6e43bb5815f4dbb88c79a0fef1c669dfba52d58',
  'doctrine/orm' => '2.10.2@81d472f6f96b8b571cafefe8d2fef89ed9446a62',
  'doctrine/persistence' => '2.2.3@5e7bdbbfe9811c06e1f745d1c166647d5c47d6ee',
  'doctrine/sql-formatter' => '1.1.2@20c39c2de286a9d3262cc8ed282a4ae60e265894',
  'egulias/email-validator' => '2.1.25@0dbf5d78455d4d6a41d186da50adc1122ec066f4',
  'friendsofphp/proxy-manager-lts' => 'v1.0.5@006aa5d32f887a4db4353b13b5b5095613e0611f',
  'laminas/laminas-code' => '4.4.3@bb324850d09dd437b6acb142c13e64fdc725b0e1',
  'monolog/monolog' => '2.3.5@fd4380d6fc37626e2f799f29d91195040137eba9',
  'phpdocumentor/reflection-common' => '2.2.0@1d01c49d4ed62f25aa84a747ad35d5a16924662b',
  'phpdocumentor/reflection-docblock' => '5.3.0@622548b623e81ca6d78b721c5e029f4ce664f170',
  'phpdocumentor/type-resolver' => '1.5.1@a12f7e301eb7258bb68acd89d4aefa05c2906cae',
  'psr/cache' => '2.0.0@213f9dbc5b9bfbc4f8db86d2838dc968752ce13b',
  'psr/container' => '1.1.2@513e0666f7216c7459170d56df27dfcefe1689ea',
  'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0',
  'psr/link' => '1.1.1@846c25f58a1f02b93a00f2404e3626b6bf9b7807',
  'psr/log' => '1.1.4@d49695b909c3b7628b6289db5479a1c204601f11',
  'sensio/framework-extra-bundle' => 'v5.6.1@430d14c01836b77c28092883d195a43ce413ee32',
  'symfony/asset' => 'v5.1.11@54a42aa50f9359d1184bf7e954521b45ca3d5828',
  'symfony/cache' => 'v5.3.10@2056f2123f47c9f63102a8b92974c362f4fba568',
  'symfony/cache-contracts' => 'v2.4.0@c0446463729b89dd4fa62e9aeecc80287323615d',
  'symfony/config' => 'v5.3.10@ac23c2f24d5634966d665d836c3933d54347e5d4',
  'symfony/console' => 'v5.1.11@d9a267b621c5082e0a6c659d73633b6fd28a8a08',
  'symfony/dependency-injection' => 'v5.3.10@be833dd336c248ef2bdabf24665351455f52afdb',
  'symfony/deprecation-contracts' => 'v2.4.0@5f38c8804a9e97d23e0c8d63341088cd8a22d627',
  'symfony/doctrine-bridge' => 'v5.1.11@290deda49060e6694f151ac4aa889467935ee3ea',
  'symfony/dotenv' => 'v5.1.11@783f12027c6b40ab0e93d6136d9f642d1d67cd6b',
  'symfony/error-handler' => 'v5.3.7@3bc60d0fba00ae8d1eaa9eb5ab11a2bbdd1fc321',
  'symfony/event-dispatcher' => 'v5.3.7@ce7b20d69c66a20939d8952b617506a44d102130',
  'symfony/event-dispatcher-contracts' => 'v2.4.0@69fee1ad2332a7cbab3aca13591953da9cdb7a11',
  'symfony/expression-language' => 'v5.1.11@13a16b1cc6e4fd4998631bfdf568d47e48844ec1',
  'symfony/filesystem' => 'v5.3.4@343f4fe324383ca46792cae728a3b6e2f708fb32',
  'symfony/finder' => 'v5.3.7@a10000ada1e600d109a6c7632e9ac42e8bf2fb93',
  'symfony/flex' => 'v1.17.2@0170279814f86648c62aede39b100a343ea29962',
  'symfony/form' => 'v5.1.11@b794bed839f11bcee9a9f30daa5cb88d311dd409',
  'symfony/framework-bundle' => 'v5.1.11@b40931adcd8386901a65b472d8ba9f34cac80070',
  'symfony/http-client' => 'v5.1.11@82f87fa4b738977937803ab8d52948d490047564',
  'symfony/http-client-contracts' => 'v2.4.0@7e82f6084d7cae521a75ef2cb5c9457bbda785f4',
  'symfony/http-foundation' => 'v5.3.10@9f34f02e8a5fdc7a56bafe011cea1ce97300e54c',
  'symfony/http-kernel' => 'v5.3.10@703e4079920468e9522b72cf47fd76ce8d795e86',
  'symfony/intl' => 'v5.1.11@930f17689729cc47d2ce18be21ed403bcbeeb6a9',
  'symfony/mailer' => 'v5.1.11@3c7ab7a402acdb453dcdd6e0b2982caacfcc9b9f',
  'symfony/mime' => 'v5.1.11@d7d899822da1fa89bcf658e8e8d836f5578e6f7a',
  'symfony/monolog-bridge' => 'v5.3.7@4ace41087254f099b6743333155071438bfb12c3',
  'symfony/monolog-bundle' => 'v3.7.1@fde12fc628162787a4e53877abadc30047fd868b',
  'symfony/notifier' => 'v5.1.11@c2ccb5b6f9b7a316b3bfefc5fec751540d620d3c',
  'symfony/options-resolver' => 'v5.3.7@4b78e55b179003a42523a362cc0e8327f7a69b5e',
  'symfony/polyfill-intl-grapheme' => 'v1.23.1@16880ba9c5ebe3642d1995ab866db29270b36535',
  'symfony/polyfill-intl-icu' => 'v1.23.0@4a80a521d6176870b6445cfb469c130f9cae1dda',
  'symfony/polyfill-intl-idn' => 'v1.23.0@65bd267525e82759e7d8c4e8ceea44f398838e65',
  'symfony/polyfill-intl-normalizer' => 'v1.23.0@8590a5f561694770bdcd3f9b5c69dde6945028e8',
  'symfony/polyfill-mbstring' => 'v1.23.1@9174a3d80210dca8daa7f31fec659150bbeabfc6',
  'symfony/polyfill-php73' => 'v1.23.0@fba8933c384d6476ab14fb7b8526e5287ca7e010',
  'symfony/polyfill-php80' => 'v1.23.1@1100343ed1a92e3a38f9ae122fc0eb21602547be',
  'symfony/polyfill-php81' => 'v1.23.0@e66119f3de95efc359483f810c4c3e6436279436',
  'symfony/process' => 'v5.1.11@d279ae7f2d6e0e4e45f66de7d76006246ae00e4d',
  'symfony/property-access' => 'v5.1.11@d99f6d52333d0798a3b5bb3a81bae789e96bae93',
  'symfony/property-info' => 'v5.1.11@d4981d21891987fce806fc94e41312fe9c131747',
  'symfony/proxy-manager-bridge' => 'v5.1.11@fd6bb40190b1719abbe831be09adf38e0744d5f5',
  'symfony/routing' => 'v5.3.7@be865017746fe869007d94220ad3f5297951811b',
  'symfony/security-bundle' => 'v5.1.11@911f6b515d515c12a4aea749b6ac688050b6a85c',
  'symfony/security-core' => 'v5.1.11@33a6d376ef0502f18bc498a076590372685f6e89',
  'symfony/security-csrf' => 'v5.3.4@94b533195cf7fb21f3fae8ce349861c6401d969e',
  'symfony/security-guard' => 'v5.2.12@9edddb0b4c97eb923ba9910050be80f539933c3b',
  'symfony/security-http' => 'v5.1.11@c3a869cc01640d14ebbbfd03046f494103ffb2fa',
  'symfony/serializer' => 'v5.1.11@76404a1e1a4eaefe94ce12740af1884149d47d96',
  'symfony/service-contracts' => 'v2.4.0@f040a30e04b57fbcc9c6cbcf4dbaa96bd318b9bb',
  'symfony/stopwatch' => 'v5.3.4@b24c6a92c6db316fee69e38c80591e080e41536c',
  'symfony/string' => 'v5.1.11@83bbb92d34881744b8021452a76532b28283dbfb',
  'symfony/translation' => 'v5.1.11@b16d3e4b2d3f78fb2444aa8d19019f033e55ec56',
  'symfony/translation-contracts' => 'v2.4.0@95c812666f3e91db75385749fe219c5e494c7f95',
  'symfony/twig-bridge' => 'v5.1.11@4421afc6e1a0ef5e7cd9b32359617b98069d1666',
  'symfony/twig-bundle' => 'v5.2.12@6f2aa369c4b7da19b3c864c48e35b26451c92e4e',
  'symfony/validator' => 'v5.1.11@c651438e159bdcbe8354320ab627d33fa7e288ff',
  'symfony/var-dumper' => 'v5.3.10@875432adb5f5570fff21036fd22aee244636b7d1',
  'symfony/var-exporter' => 'v5.3.8@a7604de14bcf472fe8e33f758e9e5b7bf07d3b91',
  'symfony/web-link' => 'v5.1.11@28e6bd9028740602c158f5c6ac8d5e2a2692812e',
  'symfony/yaml' => 'v5.1.11@6bb8b36c6dea8100268512bf46e858c8eb5c545e',
  'twig/extra-bundle' => 'v3.3.3@fa92b8301ff8878e45fe9f54ab7ad99872e080f3',
  'twig/twig' => 'v3.3.3@a27fa056df8a6384316288ca8b0fa3a35fdeb569',
  'webmozart/assert' => '1.10.0@6964c76c7804814a842473e0c8fd15bab0f18e25',
  'doctrine/data-fixtures' => '1.5.1@f18adf13f6c81c67a88360dca359ad474523f8e3',
  'doctrine/doctrine-fixtures-bundle' => '3.4.1@31ba202bebce0b66fe830f49f96228dcdc1503e7',
  'nikic/php-parser' => 'v4.13.1@63a79e8daa781cac14e5195e63ed8ae231dd10fd',
  'symfony/browser-kit' => 'v5.3.4@c1e3f64fcc631c96e2c5843b666db66679ced11c',
  'symfony/css-selector' => 'v5.3.4@7fb120adc7f600a59027775b224c13a33530dd90',
  'symfony/debug-bundle' => 'v5.3.4@356c7d2acb6bc93b1c091255068ccfb9ad55a3e0',
  'symfony/dom-crawler' => 'v5.3.7@c7eef3a60ccfdd8eafe07f81652e769ac9c7146c',
  'symfony/maker-bundle' => 'v1.34.1@c9ae401f3fa2b42881120d33ad79416630d1f2be',
  'symfony/phpunit-bridge' => 'v5.3.10@325aaf6302501ed3673cffbd3ba88db5949de8ae',
  'symfony/web-profiler-bundle' => 'v5.2.13@380038080e46eb92b3a392de646fd32b632f1c77',
  'paragonie/random_compat' => '2.*@e41021622e4c43c5233858d8ead4a84dff697bf4',
  'symfony/polyfill-ctype' => '*@e41021622e4c43c5233858d8ead4a84dff697bf4',
  'symfony/polyfill-iconv' => '*@e41021622e4c43c5233858d8ead4a84dff697bf4',
  'symfony/polyfill-php72' => '*@e41021622e4c43c5233858d8ead4a84dff697bf4',
  'symfony/polyfill-php71' => '*@e41021622e4c43c5233858d8ead4a84dff697bf4',
  'symfony/polyfill-php70' => '*@e41021622e4c43c5233858d8ead4a84dff697bf4',
  'symfony/polyfill-php56' => '*@e41021622e4c43c5233858d8ead4a84dff697bf4',
  '__root__' => 'dev-master@e41021622e4c43c5233858d8ead4a84dff697bf4',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!self::composer2ApiUsable()) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (self::composer2ApiUsable()) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }

    private static function composer2ApiUsable(): bool
    {
        if (!class_exists(InstalledVersions::class, false)) {
            return false;
        }

        if (method_exists(InstalledVersions::class, 'getAllRawData')) {
            $rawData = InstalledVersions::getAllRawData();
            if (count($rawData) === 1 && count($rawData[0]) === 0) {
                return false;
            }
        } else {
            $rawData = InstalledVersions::getRawData();
            if ($rawData === null || $rawData === []) {
                return false;
            }
        }

        return true;
    }
}
