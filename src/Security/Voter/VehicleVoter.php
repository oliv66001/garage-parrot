<?php

namespace App\Security\Voter;

use App\Entity\Vehicle;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class VehicleVoter extends Voter
{
    const EDIT = 'VEHICLE_EDIT';
    const DELETE = 'VEHICLE_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $vehicle): bool
    {
        if(!in_array($attribute, [self::EDIT, self::DELETE])){
            return false;
        }
        if(!$vehicle instanceof Vehicle){
            return false;
        }
        return true;

        // return in_array($attribute, [self::EDIT, self::DELETE]) && $vehicle instanceof Vehicle;
    }

    protected function voteOnAttribute($attribute, $vehicle, TokenInterface $token): bool
    {
        // On récupère l'utilisateur à partir du token
        $user = $token->getUser();

        if(!$user instanceof UserInterface) return false;

        // On vérifie si l'utilisateur est admin
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // On vérifie les permissions
        switch($attribute){
            case self::EDIT:
                // On vérifie si l'utilisateur peut éditer
                return $this->canEdit();
                break;
            case self::DELETE:
                // On vérifie si l'utilisateur peut supprimer
                return $this->canDelete();
                break;
        }
    }

    private function canEdit(){
        return $this->security->isGranted('ROLE_COLAB_ADMIN');
    }
    private function canDelete(){
        return $this->security->isGranted('ROLE_ADMIN');
    }
}