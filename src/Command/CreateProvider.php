<?php

namespace App\Command;

use Illuminate\Support\Stringable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Twig\Environment;

#[AsCommand(
    name: 'app:create-provider',
    description: 'Criar um novo provider para pesquisa de animes'
)]
class CreateProvider extends Command
{
    public function __construct(
        private readonly Environment $twig,
    ) {
        parent::__construct();
    }

    public function saveFile(object $className, string $content): void
    {
        $filePath = sprintf('%s/Providers/%s.php', dirname(__DIR__ . '../Providers/', 2), $className);
        file_put_contents($filePath, $content);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $data = [];
            $helper = new QuestionHelper();

            // interface: MediaProviderPropertiesInterface
            $data['providerName'] = $helper->ask($input, $output, new Question('Defina o nome do provider: '));
            $data['isEmbed'] = $helper->ask($input, $output, new ConfirmationQuestion('Os vídeos são embed? (y/N) ', false));
            $data['hasAds'] = $helper->ask($input, $output, new ConfirmationQuestion('Os vídeos possuem anúncios? (y/N) ', false));
            $data['baseUrl'] = $helper->ask($input, $output, new Question('Defina a url base dos vídeos (ex.: api/{anime}/{episode}): '));
            $data['searchRequestMethod'] = $helper->ask($input, $output, new ChoiceQuestion('Qual método da request? ', ['GET', 'POST'], 'GET'));

            // interface: MediaProviderRulesInterface
            $data['canUsePrefix'] = $helper->ask($input, $output, new ConfirmationQuestion('Pode usar prefixos na pesquisa? (y/N) ', false));
            $data['canUseSuffix'] = $helper->ask($input, $output, new ConfirmationQuestion('Pode usar sufixos na pesquisa? (y/N) ', false));
            $data['mustSerializeEpisode'] = $helper->ask($input, $output, new ConfirmationQuestion('Deve ajustar automaticamente o número do episódio? (y/N) ', true));
            $data['mustHandleResponse'] = $helper->ask($input, $output, new ConfirmationQuestion('Deve lidar com a resposta? (y/N) ', false));

            $providerName = str($data['providerName'])->title();
            $className = $providerName->remove(' ');

            $content = $this->twig->render('stubs/provider.stub.twig', [
                'CLASS_NAME' => $className,
                'BASE_URL' => $data['baseUrl'],
                'IS_EMBED' => $data['isEmbed'] ? 'true' : 'false',
                'HAS_ADS' => $data['hasAds'] ? 'true' : 'false',
                'PROVIDER_NAME' => $data['providerName'],
                'PROVIDER_SLUG' => $providerName->slug(),
                'SEARCH_REQUEST_METHOD' => $data['searchRequestMethod'],
                'CAN_USE_PREFIX' => $data['canUsePrefix'] ? 'true' : 'false',
                'CAN_USE_SUFFIX' => $data['canUseSuffix'] ? 'true' : 'false',
                'MUST_SERIALIZE_EPISODE' => $data['mustSerializeEpisode'] ? 'true' : 'false',
                'MUST_HANDLE_RESPONSE' => $data['mustHandleResponse'] ? 'true' : 'false',
            ]);

            $this->saveFile($className, $content);
            $this->registerProvider($providerName, $className);

            $output->writeln('Provider Criado com sucesso!');
            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $output->writeln('Erro ao criar Provider!');
            dump($exception);

            return Command::FAILURE;
        }
    }

    public function registerProvider(string $providerName, string $providerClass): void
    {
        $kernelPath = __DIR__ . '/../Kernel.php';
        $contents = file_get_contents($kernelPath);

        $newKey = str($providerName)->lower()->trim()->remove(' ');
        $newClass = "$providerClass::class,";
        $newNamespace = "use App\\Providers\\{$providerClass};";

        $providerUsePattern = '/^(use\s+[A-Za-z0-9_\\\\]+;)(?![\s\S]*^use\s+[A-Za-z0-9_\\\\]+;)/m';
        $updated = preg_replace($providerUsePattern,  "$1\n$newNamespace", $contents);

        $providerPattern = '/(public const PROVIDERS = \[)([\s\S]*?)(\];)/';
        $newEntryProviderPattern = "    '{$newKey}' => {$newClass}\n";

        $updated = preg_replace($providerPattern, "$1$2$newEntryProviderPattern    ];", $updated);

        file_put_contents($kernelPath, $updated);
    }
}
