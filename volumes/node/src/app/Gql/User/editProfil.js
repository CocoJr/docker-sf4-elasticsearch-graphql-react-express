/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import gql from "graphql-tag";
import {ErrorFragment} from '../Fragment/error';
import {UserBaseFragment} from "../Fragment/user";

export default gql`
  mutation userEditProfil($username: String, $email: String, $password: String, $passwordConfirm: String) {
    userEditProfil(input: {username: $username, email: $email, password: $password, passwordConfirm: $passwordConfirm}) {
      user {
        ...UserBaseFragment
      }
      errors {
        ...ErrorFragment
      }
    }
  }
  ${UserBaseFragment}
  ${ErrorFragment}
`;
