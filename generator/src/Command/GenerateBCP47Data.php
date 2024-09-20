<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarExporter\VarExporter;

use function ICanBoogie\CLDR\Generator\indent;

#[AsCommand(self::GENERATED_FILE)]
final class GenerateBCP47Data extends Command
{
    private const GENERATED_FILE = 'src/BCP47/BCP47Data.php';

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bcp47 = $this->repository->bcp47;
        $cal = $bcp47['calendar'];
        $col = $bcp47['collation'];
        $cur = $bcp47['currency'];
        $mea = $bcp47['measure'];
        $num = $bcp47['number'];
        $seg = $bcp47['segmentation'];
        $tim = $bcp47['timezone'];
        $var = $bcp47['variant'];
        $trd = $bcp47['transform-destination'];
        $tra = $bcp47['transform'];
        $trh = $bcp47['transform_hybrid'];
        $tri = $bcp47['transform_ime'];
        $trk = $bcp47['transform_keyboard'];
        $trm = $bcp47['transform_mt'];
        $trp = $bcp47['transform_private_use'];

        $contents = $this->render(
            u_ca: indent(VarExporter::export(self::relevant_keys($cal['ca'])), 2),
            u_fw: indent(VarExporter::export(self::relevant_keys($cal['fw'])), 2),
            u_hc: indent(VarExporter::export(self::relevant_keys($cal['hc'])), 2),
            u_co: indent(VarExporter::export(self::relevant_keys($col['co'])), 2),
            u_ka: indent(VarExporter::export(self::relevant_keys($col['ka'])), 2),
            u_kb: indent(VarExporter::export(self::relevant_keys($col['kb'])), 2),
            u_kc: indent(VarExporter::export(self::relevant_keys($col['kc'])), 2),
            u_kf: indent(VarExporter::export(self::relevant_keys($col['kf'])), 2),
            u_kh: indent(VarExporter::export(self::relevant_keys($col['kh'])), 2),
            u_kk: indent(VarExporter::export(self::relevant_keys($col['kk'])), 2),
            u_kn: indent(VarExporter::export(self::relevant_keys($col['kn'])), 2),
            u_kr: indent(VarExporter::export(self::relevant_keys($col['kr'])), 2),
            u_ks: indent(VarExporter::export(self::relevant_keys($col['ks'])), 2),
            u_kv: indent(VarExporter::export(self::relevant_keys($col['kv'])), 2),
            u_vt: indent(VarExporter::export(self::relevant_keys($col['vt'])), 2),
            u_cf: indent(VarExporter::export(self::relevant_keys($cur['cf'])), 2),
            u_cu: indent(VarExporter::export(self::relevant_keys($cur['cu'])), 2),
            u_ms: indent(VarExporter::export(self::relevant_keys($mea['ms'])), 2),
            u_mu: indent(VarExporter::export(self::relevant_keys($mea['mu'])), 2),
            u_nu: indent(VarExporter::export(self::relevant_keys($num['nu'])), 2),
            u_dx: indent(VarExporter::export(self::relevant_keys($seg['dx'])), 2),
            u_lb: indent(VarExporter::export(self::relevant_keys($seg['lb'])), 2),
            u_lw: indent(VarExporter::export(self::relevant_keys($seg['lw'])), 2),
            u_ss: indent(VarExporter::export(self::relevant_keys($seg['ss'])), 2),
            u_tz: indent(VarExporter::export(self::relevant_keys($tim['tz'])), 2),
            u_em: indent(VarExporter::export(self::relevant_keys($var['em'])), 2),
            u_rg: indent(VarExporter::export(self::relevant_keys($var['rg'])), 2),
            u_sd: indent(VarExporter::export(self::relevant_keys($var['sd'])), 2),
            u_va: indent(VarExporter::export(self::relevant_keys($var['va'])), 2),
            t_d0: indent(VarExporter::export(self::relevant_keys($trd['d0'])), 2),
            t_s0: indent(VarExporter::export(self::relevant_keys($trd['s0'])), 2),
            t_m0: indent(VarExporter::export(self::relevant_keys($tra['m0'])), 2),
            t_h0: indent(VarExporter::export(self::relevant_keys($trh['h0'])), 2),
            t_i0: indent(VarExporter::export(self::relevant_keys($tri['i0'])), 2),
            t_k0: indent(VarExporter::export(self::relevant_keys($trk['k0'])), 2),
            t_t0: indent(VarExporter::export(self::relevant_keys($trm['t0'])), 2),
            t_x0: indent(VarExporter::export(self::relevant_keys($trp['x0'])), 2),
        );

        file_put_contents(self::GENERATED_FILE, $contents);

        return self::SUCCESS;
    }

    /**
     * @param array<string, mixed> $ar
     *
     * @return array<string>
     */
    private function relevant_keys(array $ar): array
    {
        $ar = array_filter($ar, fn($k) => !str_starts_with($k, '_'), ARRAY_FILTER_USE_KEY);

        return array_keys($ar);
    }

    private function render(
        string $u_ca,
        string $u_fw,
        string $u_hc,
        string $u_co,
        string $u_ka,
        string $u_kb,
        string $u_kc,
        string $u_kf,
        string $u_kh,
        string $u_kk,
        string $u_kn,
        string $u_kr,
        string $u_ks,
        string $u_kv,
        string $u_vt,
        string $u_cf,
        string $u_cu,
        string $u_ms,
        string $u_mu,
        string $u_nu,
        string $u_dx,
        string $u_lb,
        string $u_lw,
        string $u_ss,
        string $u_tz,
        string $u_em,
        string $u_rg,
        string $u_sd,
        string $u_va,
        string $t_d0,
        string $t_s0,
        string $t_m0,
        string $t_h0,
        string $t_i0,
        string $t_k0,
        string $t_t0,
        string $t_x0,
    ): string {
        $class = __CLASS__;

        return <<<PHP
        <?php

        /**
         * CODE GENERATED; DO NOT EDIT.
         *
         * {@see \\$class}
         */

        namespace ICanBoogie\CLDR\BCP47;

        /**
         * @internal
         * @codeCoverageIgnore
         */
        final class BCP47Data
        {
            /**
             * Possible values for `u-ca`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_CA =
        $u_ca;

            /**
             * Possible values for `u-co`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/collation.json
             */
            public const U_CO =
        $u_co;

            /**
             * Possible values for `u-fw`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_FW =
        $u_fw;

            /**
             * Possible values for `u-hc`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_HC =
        $u_hc;

            /**
             * Possible values for `u-ka`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KA =
        $u_ka;

            /**
             * Possible values for `u-kb`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KB =
        $u_kb;

            /**
             * Possible values for `u-kc`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KC =
        $u_kc;

            /**
             * Possible values for `u-kf`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KF =
        $u_kf;

            /**
             * Possible values for `u-kh`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KH =
        $u_kh;

            /**
             * Possible values for `u-kk`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KK =
        $u_kk;

            /**
             * Possible values for `u-kn`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KN =
        $u_kn;

            /**
             * Possible values for `u-kr`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KR =
        $u_kr;

            /**
             * Possible values for `u-ks`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KS =
        $u_ks;

            /**
             * Possible values for `u-kv`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_KV =
        $u_kv;

            /**
             * Possible values for `u-vt`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/calendar.json
             */
            public const U_VT =
        $u_vt;

            /**
             * Possible values for `u-cf`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/currency.json
             */
            public const U_CF =
        $u_cf;

            /**
             * Possible values for `u-cu`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/currency.json
             */
            public const U_CU =
        $u_cu;

            /**
             * Possible values for `u-ms`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/measure.json
             */
            public const U_MS =
        $u_ms;

            /**
             * Possible values for `u-mu`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/measure.json
             */
            public const U_MU =
        $u_mu;

            /**
             * Possible values for `u-nu`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/number.json
             */
            public const U_NU =
        $u_nu;

            /**
             * Possible values for `u-dx`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/segmentation.json
             */
            public const U_DX =
        $u_dx;

            /**
             * Possible values for `u-lb`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/segmentation.json
             */
            public const U_LB =
        $u_lb;

            /**
             * Possible values for `u-lw`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/segmentation.json
             */
            public const U_LW =
        $u_lw;

            /**
             * Possible values for `u-ss`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/segmentation.json
             */
            public const U_SS =
        $u_ss;

            /**
             * Possible values for `u-tz`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/timezone.json
             */
            public const U_TZ =
        $u_tz;

            /**
             * Possible values for `u-em`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/variant.json
             */
            public const U_EM =
        $u_em;

            /**
             * Possible values for `u-rg`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/variant.json
             */
            public const U_RG =
        $u_rg;

            /**
             * Possible values for `u-sd`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/variant.json
             */
            public const U_SD =
        $u_sd;

            /**
             * Possible values for `u-va`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/variant.json
             */
            public const U_VA =
        $u_va;

            /**
             * Possible values for `t-d0`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/transform-destination.json
             */
            public const T_D0 =
        $t_d0;

            /**
             * Possible values for `t-s0`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/transform-destination.json
             */
            public const T_S0 =
        $t_s0;

            /**
             * Possible values for `t-m0`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/transform.json
             */
            public const T_M0 =
        $t_m0;

            /**
             * Possible values for `t-h0`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/transform_hybrid.json
             */
            public const T_H0 =
        $t_h0;

            /**
             * Possible values for `t-i0`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/transform_ime.json
             */
            public const T_I0 =
        $t_i0;

            /**
             * Possible values for `t-k0`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/transform_keyboard.json
             */
            public const T_K0 =
        $t_k0;

            /**
             * Possible values for `t-t0`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/transform_mt.json
             */
            public const T_T0 =
        $t_t0;

            /**
             * Possible values for `t-x0`.
             *
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-bcp47/bcp47/transform_private_use.json
             */
            public const T_X0 =
        $t_x0;

            public const U_MAPPING =
                [
                    'ca' => self::U_CA,
                    'cf' => self::U_CF,
                    'co' => self::U_CO,
                    'cu' => self::U_CU,
                    'dx' => self::U_DX,
                    'em' => self::U_EM,
                    'fw' => self::U_FW,
                    'hc' => self::U_HC,
                    'lb' => self::U_LB,
                    'lw' => self::U_LW,
                    'ms' => self::U_MS,
                    'mu' => self::U_MU,
                    'nu' => self::U_NU,
                    'rg' => self::U_RG,
                    'sd' => self::U_SD,
                    'ss' => self::U_SS,
                    'tz' => self::U_TZ,
                    'va' => self::U_VA,
                ];

            private function __construct()
            {
            }
        }

        PHP;
    }
}
