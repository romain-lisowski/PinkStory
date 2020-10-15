<template>
  <div>
    <div class="relative h-screen">
      <div
        class="absolute w-screen h-screen bg-center bg-cover"
        :style="{
          '-webkit-filter': 'blur(8px)',
          'background-image': `url(${require('@/assets/images/1.jpg')})`,
        }"
      ></div>
      <div class="absolute pt-48 w-screen h-screen bg-primary bg-opacity-50">
        <img
          class="mx-auto w-1/4 object-cover rounded-2xl"
          :src="require(`@/assets/images/${story.imagePath}`)"
        />
        <h2
          class="mt-20 text-7xl font-bold text-primary tracking-tighter leading-none"
        >
          {{ story.parentTitle }}
        </h2>
        <h1 class="text-4xl text-primary">
          {{ story.title }}
        </h1>

        <p
          class="inline-block mt-20 px-3 py-1 text-2xl text-primary border-2 rounded-l-full rounded-r-full"
        >
          <font-awesome-icon icon="heart" class="mr-2" />
          {{ $t('add-to-favorites') }}
        </p>
      </div>
    </div>

    <div class="relative">
      <ul
        class="flex justify-center items-center w-full h-32 text-primary-inverse bg-accent"
      >
        <li class="flex flex-col px-8 border-r">
          <span class="text-xl">{{ $t('reader-reviews') }}</span>
          <span class="text-4xl font-bold">{{ story.rating }} / 5</span>
        </li>
        <li class="flex flex-col px-8 border-r">
          <span class="text-xl">{{ $t('comments') }}</span>
          <span class="text-4xl font-bold">{{ story.nbComments }}</span>
        </li>
        <li class="flex flex-col px-8 border-r">
          <span class="text-xl">{{ $t('reading-time') }}</span>
          <span class="text-4xl font-bold">{{ story.readingTime }} min</span>
        </li>
        <li class="flex flex-col pl-8">
          <span class="text-xl">{{ $t('first-publication') }}</span>
          <span class="text-4xl font-bold">{{ story.updatedAt }}</span>
        </li>
      </ul>

      <div class="flex flex-col items-center bg-primary-inverse bg-opacity-5">
        <p
          class="w-2/3 pt-12 text-justify font-thin text-xl tracking-wide leading-relaxed"
          v-html="$sanitize(story.content)"
        ></p>

        <span class="text-xl font-bold"
          >{{ $t('categories') }} :
          <span class="text-xl font-normal text-accent-highlight">{{
            story.categories
          }}</span>
        </span>

        <div class="flex justify-around w-full my-12">
          <div class="flex items-center">
            <font-awesome-icon icon="chevron-left" class="text-4xl mr-4" />
            <span class="flex flex-col">
              <span class="text-xl text-left">Chapitre 11</span>
              <span class="font-bold">{{ story.previousChapter }}</span>
            </span>
          </div>
          <div class="flex items-center">
            <span class="flex flex-col">
              <span class="text-xl text-right">Chapitre 13</span>
              <span class="font-bold">{{ story.nextChapter }}</span>
            </span>
            <font-awesome-icon icon="chevron-right" class="text-4xl ml-4" />
          </div>
        </div>

        <p class="text-4xl font-bold">{{ $t('informations') }}</p>
        <div class="flex justify-evenly gap-8 mt-6">
          <div class="p-8 border rounded-xl">
            <img
              class="mx-auto w-48 object-cover rounded-2xl"
              :src="require(`@/assets/images/${story.imagePath}`)"
            />
            <p class="py-4 text-xl font-bold">{{ story.parentTitle }}</p>
            <div class="flex">
              <div class="flex flex-col pr-6 border-r-2">
                <span class="text-base">{{ $t('reader-reviews') }}</span>
                <span class="text-xl font-bold">{{ story.rating }} / 5</span>
              </div>
              <div class="flex flex-col pl-6">
                <span class="text-base">{{ $t('chapters') }}</span>
                <span class="text-xl font-bold">{{ story.nbChapters }}</span>
              </div>
            </div>
          </div>
          <div class="p-8 border rounded-xl">
            <img
              class="mx-auto w-24 rounded-full"
              :src="require('@/assets/images/profil.jpg')"
            />
            <p class="py-4 text-xl font-bold">{{ story.author }}</p>
            <div class="flex">
              <div class="flex flex-col pr-6 border-r-2">
                <span class="text-base">{{ $t('stories') }}</span>
                <span class="text-xl font-bold">{{ story.nbStories }}</span>
              </div>
              <div class="flex flex-col pl-6">
                <span class="text-base">{{ $t('registration') }}</span>
                <span class="text-xl font-bold">{{ story.registration }}</span>
              </div>
            </div>
          </div>
        </div>

        <p class="text-4xl font-bold mt-10">{{ $t('comments') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Story',
  data() {
    return {
      story: {
        author: 'Estelle48',
        gender: 'female',
        date: '26/09/2020',
        parentTitle: 'Chantage et soumission',
        nbChapters: 26,
        title: 'Une histoire de cul bien tapée - chapitre 12',
        categories: 'Hétéro, Triolisme, Hard, Candaulisme, Au bureau',
        rating: 4.5,
        nbComments: 5659,
        nbStories: 43903,
        registration: '26 sept 2020',
        imagePath: '4.png',
        readingTime: 20,
        updatedAt: '13 oct 2020',
        previousChapter: 'Ma femme, cette salope',
        nextChapter: 'La soumise',
        content: `<p>J'ai une très bonne amie, Caroline, qui est mariée à Marc un homme sympathique qui a créé sa boîte. Eric, mon mari, ne peut pas l'encadrer. Il faut dire que mon mari travaille dans une usine où il est responsable syndical et que les patrons, même petit, sont pour lui que des escrocs qui exploitent des gars comme lui. Bref, du coup nous avons peu l'occasion de nous voir avec Caro, ou lorsque nous déjeunons ensemble, nous le faisons sans nos maris respectifs.</p><br/>

          <p>Il y a deux mois, ils ont déménagé à 3 heures de route de chez nous et du coup nous ne nous voyons plus du tout. Elle me manque beaucoup car nous étions très proches, alors quand elle nous a invité à venir passer le week-end dans leur nouvelle maison, j'ai dit oui tout de suite.
          Bien évidemment, Eric n'était pas content mais j'avais envie et besoin de revoir mon amie. Du coup il a accepté pour me faire plaisir.</p><br/>

          <p>Nous sommes partis le vendredi après son boulot et sommes arrivés chez Caro et Marc vers 20h00. Ils nous attendaient au bord de leur superbe piscine. Eric bougonnait et trouvait d'entrée que Marc nous jetait son pognon au visage avec cette invitation. Il était ridicule et je ne prêtais pas attention à lui, préférant discuter avec ma copine avec laquelle je voulais rattraper le temps perdu.</p><br/>

          <p>Cette première soirée chez eux a été très agréable même si nos deux maris n'ont cessé de s'envoyer des pics. En plus de leur situation professionnelle à l'opposé l'une de l'autre, ils supportaient en plus deux équipes rivales, le PSG et l'OM !! Autant dire que la soirée fut tendue et à limite de l'amabilité.</p><br/>

          <p>A un moment Caro voulut détendre l'atmosphère et les chambrer un peu et dit :
          - ils ont fini de se comparer leur zizi ces deux là !!</p><br/>

          <p>Ce à quoi Marc dit avec un grand sourire que même de ce côté là Eric n'était pas à la hauteur. Leurs remarques puériles continuèrent encore un bon moment jusqu'au moment où on alla se coucher.</p><br/>

          <p>Caro et Marc nous avaient préparé une belle et grande chambre à l'étage, au bout du couloir, à l'opposé de la leur.</p><br/>

          <p>Sitôt la porte fermée, je fis des remontrances à mon mari, lui demandant de se calmer avec Marc, qu'il était ridicule ! Il me prit dans ses bras et m'embrassa de force, ne me laissant plus l'engueuler. J'aime quand il est viril comme ça. Il m'a littéralement mangé la bouche, me pelotant les fesses en même temps et me faisant nettement sentir son désir. Il avait terriblement envie de moi et je ne pus pas faire grand chose pour le retenir. En quelques instants, on se retrouva nu dans les bras l'un de l'autre, se bécotant comme des ados. Son sexe tendu se planta rapidement entre mes cuisses et il se mit à me baiser comme rarement. Je tentais d'étouffer mes gémissements en me mordant les lèvres mais il se déchaînait avec une telle vigueur que plusieurs fois des petits cris m'échappèrent. Il me retourna et se remit à aller et venir avec la même fougue. Son bas ventre claquait sur mes fesses et je plongeais ma tête pour étouffer mes cris dans l'oreiller. Eric était déchaîné et le lit craquait sous ses assauts répétés.
          Alors qu'un délicieux orgasme me traversait il arrêta enfin et dans un râle que toute la maison dut entendre, il jouit sur mes fesses, m'aspergeant celles-ci mais également le bas de mon dos.</p><br/>

          <p>Il se laissa choir à côté de moi, couvert de sueur. Je mis un moment à retrouver mes esprits et tandis que le silence s'était de nouveau installé dans notre chambre, des bruits similaires nous provenaient de la chambre occupée par nos amis. Nos ébats avaient dû leur donner des idées et à en croire les cris et gémissements de Caro, son mari avait l'air d'être dans le même état d'excitation que le mien !!!</p><br/>

          <p>Je compris alors que nos deux époux continuaient leur concours de "zizi" mais cette fois-ci à distance et en se servant de nous ! Nous eûmes ainsi droit aux râles de plaisir de Marc qui devait à son tour se déverser dans ou sur sa femme.</p><br/>

          <p>Tout cela était très excitant et vu le sexe d'Eric qui avait retrouvé une belle érection, je n'étais pas la seule à le penser. Je me penchais sur lui et le pris dans ma bouche pour une douce fellation. Une fois le sexe tendu, je vins à califourchon sur lui et le laissais me pénétrer. Nous fîmes alors l'amour avec plus de tendresse et nous avons joui tous les deux en même temps quelques instants plus tard.</p><br/>

          <p>Le lendemain matin, Eric se leva avant moi comme d'habitude. Je le retrouvais bien plus tard en compagnie d'Eric. Curieusement, ils semblaient avoir enterré la hache de guerre et discutait très simplement entre eux. Caro arriva peut de temps après moi et on déjeuna toutes les deux. On échangea discrètement sur la nuit passée et nos maris déchaînés. Elle m'avoua que de nous avoir entendu avait rendu son mari complètement dingue, ce à quoi je répondis la même chose !!!</p><br/>

          <p>On profita de la matinée pour aller faire du shopping toutes les deux laissant nos maris ensemble et en espérant ne pas les retrouver entrain de se battre. Au contraire, à notre retour, ils étaient entrain de trinquer sur la terrasse. Le barbecue était prêt et on passa à table dans la foulée.</p><br/>

          <p>Après le repas, Caro proposa qu'on aille piquer une tête. Eric et moi allâmes nous changer mais comme une gourde, j'avais complètement oublié de prendre nos maillots. Pas de soucis pour mon mari qui faisait la même taille que Marc. Ce dernier lui passa un maillot. Pour moi c'était plus compliqué. Caro me donna un bas à elle mais pour le haut, notre différence de tour de poitrine ne me permettait pas de lui emprunter de haut. A que cela ne tienne, elle me dit que je n'avais qu'à ne pas en porter et que par solidarité, elle ferait également topless.</p><br/>

          <p>A notre arrivée au bord de la piscine, je vis le regard ravi de nos maris. Ils n'en ratèrent pas une miette, Eric ravit de découvrir les seins de ma copine et Marc tout aussi content de jeter un oeil sur les miens !</p><br/>

          <p>On se baigna toute l'après-midi. Avec cette chaleur, cette piscine était une riche idée.</p><br/>

          <p>Initialement, Caro m'avait dit que nous irions dans un restaurant sélect du coin mais finalement, elle nous proposa que nous restions à la maison. Malgré tout, elle nous demanda de nous faire tous beaux pour ce moment entre nous. Chacun de notre côté nous nous préparâmes pour la soirée. Mon mari enfila une chemise blanche tandis que je mettais une robe fourreau noire et plutôt décolletée. En me voyant ainsi il haussa les épaules et me dit que c'était moche avec les traces de mon soutien-gorge et de ma petite culotte.
          Sa remarque était justifiée, je lui demandais alors s'il préférait que je change de robe ou que je retire mes sous-vêtements. Sa réponse laconique :"tu fais comme tu veux!" me surprit beaucoup et je décidais de lui jouer un tour en retirant mes sous-vêtements me retrouvant ainsi nue sous le léger tissu de la robe.</p><br/>

          <p>Il m'enlaça et m'embrassa en me disant que j'étais superbe. Nous rejoignîmes nos amis qui nous attendaient dans le salon. Marc était très élégant et Caro était super sexy dans une robe très moulante rouge fendue sur le côté. Perchée sur des hauts talons, cela lui faisait un cul superbe !! Je vis tout de suite que cela n'avait pas échappé à Eric comme je vis que ma tenue semblait plaire à notre hôte.</p><br/>

          <p>On s'installa au salon et je pris soin de tirer un peu sur les pans de ma robe au moment de m'asseoir afin de ne pas dévoiler mon intimité. Caro, elle, n'avait pas pris autant de précautions et sa robe dévoila ses cuisses jusque très haut. Marc nous servit une coupe Champagne et on trinqua à ce week-end fort sympathique.</p><br/>
          L'ambiance était très bonne et chaleureuse et même nos maris respectifs semblaient se plaire à discuter ensemble.</p><br/>

          <p>Marc mit un fond sonore et nous invita à onduler nos corps sur les rythmes de la musique. Caro et moi ne nous fîmes pas prier et je suivais mon amie dans ses mouvements de danse très lascives et sexys. Nos maris n'en loupaient pas une miette, trinquant et discutant en nous regardant. Il semblait y avoir une vraie complicité entre eux.
          Caro leur fit signe de nous rejoindre. On dansa avec nos hommes et à un moment Caro proposa qu'on change de cavalier. Ainsi je me retrouvais dans les bras de Marc qui se colla immédiatement à moi, mon mari faisant de même avec Caro.</p><br/>

          <p>On dansa ainsi un long moment. Les mains de Marc devenaient quelque peu baladeuses, se promenant de mes reins à mes fesses, remontant parfois le long de mes hanches pour effleurer mes seins libres de tout soutien. Il put se rendre compte rapidement que je ne portais rien dessous et je sentis que cette découverte développait une certaine partie de sa personne. Je sentais en effet une bosse que je devinais volumineuse contre mon bas ventre.
          De son côté Eric ne restait pas en reste avec Caro et ses mains faisaient le même manège sur le corps de mon amie. La situation devenait vraiment très excitante et une douce chaleur s'installait dans mon corps.
          Je continuais d'onduler et pris un malin plaisir à me frotter doucement au sexe de mon cavalier que je sentais grossir encore. Je surveillais du coin de l'oeil mon mari et mon amie qui dansaient très collés. La main de Caro s'était glissée dans les cheveux d'Eric pour les caresser tandis que les mains de mon mari étaient descendues sur les fesses de sa partenaire et les palpaient sans gêne. Caro me fit un large sourire et un petit clin d'oeil et en même temps, elle attira la tête de mon mari vers elle. Je vis leur baiser sensuel à quelques pas de moi et cela m'excita au plus haut point. Eric me regarda interrogateur et pour seule réponse je me saisis de Marc à la nuque et l'attirait vers moi. Ses lèvres se soudèrent aux miennes et nos langues s'enroulèrent l'une autour de l'autre.
          Je fermais les yeux, dégustant cet instant hautement érotique.</p><br/>

          <p>Nous restâmes soudés un bon moment tout comme le nouveau couple formé par mon mari et mon amie. Lorsque je relâchais mon étreinte, je vis Caro qui entraînait mon mari vers les canapés. Je l'observais pousser Eric dans l'un d'eux. Elle l'enjamba et s'assit sur ses cuisses, faisant remonter très haut sa robe et dévoilant ses fesses. Elle se remit à l'embrasser tandis que je voyais les mains de mon mari glisser sous le tissu de la robe, empoignant les fesses somptueuses de sa partenaire.</p><br/>

          <p>Marc voyant cela m'embrassa de nouveau, glissant ses lèvres dans mon cou, puis sur mon décolleté. Je sentis ses mains remonter sur mes côtes puis soupeser mes seins libres de tout soutien.
          Il se pencha un peu et les embrassa au travers du tissu, en même temps ses mains glissèrent sur mes cuisses et se saisirent de ma robe qu'il remonta lentement. Je levais les bras et il me débarrassa de mon seul vêtement, dévoilant mon corps nu. Il se jeta de nouveau sur mes seins qu'il embrassa avec passion.</p><br/>

          <p>J'en profitais, pendant cette caresse délicieuse, pour regarder Caro qui avait pris une longueur d'avance avec mon mari. Elle avait glissé à ses pieds et s'était empressé de dégrafer son pantalon. Elle avait désormais le sexe de mon homme entre ses lèvres et le suçait goulûment !!
          En voyant cela, l'envie de faire de même avec son mari me poussa à me jeter à ses pieds. A genoux devant Marc, je dégrafais fébrilement son pantalon. Son boxer gonflé semblait prêt à exploser. Je tirais sur l'élastique et extirpais un sexe énorme, effectivement beaucoup plus gros que celui de mon mari. Il était plus court mais avec un diamètre incroyable. J'ouvris la bouche et eus des difficultés à le prendre en bouche. Je m'appliquais malgré tout sur ce morceau de choix constatant que Marc ne s'était pas vanté !!</p><br/>

          <p>Après quelques minutes de fellation, il m'aida à me relever et m'entraîna vers le deuxième canapé. Il s'y assit et m'attira vers lui. Je l'enfourchais et m'assis sur son sexe dressé. Il me pénétra sans autres préliminaires et je sentis chaque centimètre de son sexe s'enfoncer en moi.
          En face de nous, Caro était désormais dans la même position que moi et je l'entendais gémir.</p><br/>`,
      },
    }
  },
}
</script>

<i18n>
{
  "fr": {
    "add-to-favorites": "Ajouter aux favoris",
    "reader-reviews": "Avis des lecteurs",
    "comments": "Commentaires",
    "reading-time": "Temps de lecture",
    "first-publication": "Première publication",
    "categories": "Catégories",
    "chapters": "Chapitres",
    "stories": "Histoires",
    "registration": "Inscription",
    "informations": "Informations",
    "comments": "Commentaires"
  }
}
</i18n>
