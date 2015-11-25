<?php
namespace Dende\MultidatabaseBundle\Controller;

use Gyman\Bundle\AppBundle\Globals;
use Gyman\Bundle\ClubBundle\Entity\Club;
use Dende\MultidatabaseBundle\Connection\ConnectionWrapper;
use Dende\MultidatabaseBundle\Services\CredentialsStorage;
use Gyman\Bundle\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/switch-club-form", name="gyman_default_clubswitch")
     * @Method("GET")
     * @Template()
     * @return array
     */
    public function switchClubAction()
    {
        $formType = $this->get('dende.multidatabase.form.club_switch');

        return [
            'form' => $this->createForm($formType)->createView(),
        ];
    }

    /**
     * @Route("/set-current-club", name="dende_multidatabase_set_current_club")
     * @Method("POST")
     */
    public function setCurrentClubAction(Request $request)
    {
        $form = $this->createForm($this->get('dende.multidatabase.form.club_switch'));

        $form->handleRequest($request);

        if ($form->isValid()) {

            /** @var Club $club */
            $club = $form->getData()['club'];

            /* @var User $user */
            $user = $this->getUser();
            $user->setCurrentClub($club);

            $em = $this->getDoctrine()->getManager('default');
            $em->persist($user);
            $em->flush();

            $db = $club->getDatabase();

            $this->get('session')->set(
                Globals::CURRENT_CLUB_SESSION_KEY,
                $club
            );

            /* @var ConnectionWrapper $connection */
            $connection = $this->get('doctrine.dbal.club_connection');
            $connection->forceSwitch(
                $db[CredentialsStorage::PARAM_BASE],
                $db[CredentialsStorage::PARAM_USER],
                $db[CredentialsStorage::PARAM_PASS]
            );
        }

        return new RedirectResponse($this->generateUrl('_dashboard_index'));
    }
}
